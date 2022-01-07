<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\MediaImgController;
use App\Http\Controllers\MediaTextController;
use App\Http\Controllers\MediaFigureController;
use App\Http\Controllers\MediaAudioController;
use App\Http\Controllers\MediaMovieController;
use App\Http\Controllers\MediaContentsFieldController;
use App\Http\Controllers\MediaSettingController;

use App\Lib\ImgUtil;
use App\Lib\MediaImgUtil;
use App\Lib\MediaTextUtil;
use App\Lib\MediaFigureUtil;
use App\Lib\PublicImgUtil;
use App\Lib\UserOwnImgUtil;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\UserOwnImg;
use App\Models\PublicImg;
use App\Models\MediaImg;
use App\Models\MediaText;
use App\Models\MediaFigure;
use App\Models\MediaAudio;
use App\Models\MediaMovie;
use App\Models\MediaContentsField;
use App\Models\MediaSetting;
use Storage;

class MediaUtil
{


  // 指定のmedia情報を取得
  public static function getMediaDatas($media_id){
    $media_img_data      = MediaImgController::show($media_id);
    $media_audios_data   = MediaAudioController::show($media_id);
    $media_movie_data    = MediaMovieController::show($media_id);
    $contents_field_data  = MediaContentsFieldController::show($media_id);
    $media_setting_data  = MediaSettingController::show($media_id);

    $data = [
        'media_img' => $media_img_data,
        'media_audios' => $media_audios_data,
        'media_movie' => $media_movie_data,
        'contents_field_data' => $contents_field_data,
        'media_setting' => $media_setting_data,
    ];
    return $data;
  }


  // 作成済みmediaのプレビュー情報を取得
  public static function getCreatedMediaPreviewInfos($record_num=100){
    $authenticated_userId = Auth::user()->id;
    $createdMediaPreviewInfos = array();
    $medias = Media::limit($record_num)
            ->where('user_id', $authenticated_userId)
            ->get();

    foreach($medias as $index => $media){
        $media_id = $media->id;
        $createdMediaPreviewInfos[] = MediaUtil::getMediaPreviewInfo($media_id);
    }

    return ['createdMediaPreviewInfos' => $createdMediaPreviewInfos];
  }


  // いいねしたmediaのプレビュー情報を取得
  public static function getLikedMediaPreviewInfos($record_num=100){
    $authenticated_userId = Auth::user()->id;
    $likedMediaPreviewInfos = array();
    $medias = MediaUtil::getLikedMediaModel($authenticated_userId, $record_num);
    foreach($medias as $index => $media){
        $media_id = $media->media_id; // ※idではなくmedia_idで指定する
        $likedMediaPreviewInfos[] = MediaUtil::getMediaPreviewInfo($media_id);
    }
    return ['likedMediaPreviewInfos' => $likedMediaPreviewInfos];
  }
  

  // 自分がいいねしたmediaのmodelを返す
  public static function getLikedMediaModel($user_id, $record_num){
    $sql = <<< SQL
      SELECT * FROM medias r
        INNER JOIN user_like_medias ulm
        ON ulm.media_id = r.id
        WHERE ulm.user_id = $user_id
        LIMIT $record_num
    SQL;

    $medias = DB::select($sql);
    return $medias;  
  }


  // 公開中のmediaのmodelを返す
  public static function getOpenMediaModel($record_num){
    $sql = <<< SQL
      SELECT * FROM medias r
        INNER JOIN media_settings rs
        ON rs.media_id = r.id
        WHERE rs.open_state = 1
        LIMIT $record_num
    SQL;

    $medias = DB::select($sql);
    return $medias;
  }


  // Mediaのプレビュー表示に必要な情報を取得(id,title,サムネ画像のurl)
  public static function getMediaPreviewInfo($media_id){
    $media_name = MediaSetting::where('media_id', $media_id)->first()->name;
    $media_img_url = MediaUtil::getMediaPreviewImgUrl($media_id);

    $mediaInfo = array(
        'id' => $media_id,
        'name' => $media_name,
        'preview_img_url' => $media_img_url,
        'selectedOrderNum' => 0,
    );
    return $mediaInfo;
  }


  // 画像、動画、音楽の設定状況に応じたプレビュー表示用画像を設定する
  // 優先度は、1:画像、2:動画、3:音楽 1,2,3どれもなければempty画像を設定
  public static function getMediaPreviewImgUrl($media_id){
    $media_img_url;
    $imgPattern = 0;
    if(MediaImg::where('media_id', $media_id)->exists() && MediaImg::where('media_id', $media_id)->first()->img_id != 0){
      $imgPattern = 1;
    } else if(MediaMovie::where('media_id', $media_id)->exists()){
      $imgPattern = 2;
    } else if(MediaAudio::where('media_id', $media_id)->exists()){
      $imgPattern = 3;
    }

    switch ($imgPattern){
      case 1:
        $media_img_id = MediaImg::where('media_id', $media_id)->first()->img_id;
        $media_img_type = MediaImg::where('media_id', $media_id)->first()->img_type;
        $media_img = MediaImgUtil::getMediaImgModel($media_img_id, $media_img_type);
        $media_img_url = $media_img->img_url;
        break;        
      case 2:
        $media_img_url = "https://".config('app.aws_bucket').".s3.".config('app.aws_default_region').".amazonaws.com/app-decomedia/3oLdT6SSOkEUW0ejXRWsLX177aXQVOd5vRa8Qtse.png";
        break;
      case 3:
        $media_img_url = "https://".config('app.aws_bucket').".s3.".config('app.aws_default_region').".amazonaws.com/app-decomedia/t6xoK6A2Wgy33J82wCzEvW12pnLqmeDkF4ASzqtO.jpg";
        break;
      default:
        $media_img_url = "https://".config('app.aws_bucket').".s3.".config('app.aws_default_region').".amazonaws.com/app-decomedia/tyOKqvszOb4LDP2egK6qTqWFzFiFnxlCurxaf98W.png";
    }
    
    return $media_img_url;
  }
    


  // media情報をDBに保存
  public static function saveMediaDataInDB($request){
    // DB::beginTransaction();
    try{
      // media保存
      $media = new Media(); 
      // $media->user_id = Auth::user()->id;
      $model = $media->create(['user_id' => Auth::user()->id]);
      
      // 保存したmediaのidを取得
      $media_id = $model->id;
  
      // media画像
      if(isset($request->imgs[0]['id'])){
        MediaImgController::store($media_id, $request->imgs);
      }
      // media動画
      if(isset($request->movie['videoId'])){
        MediaMovieController::store($media_id, $request);
      }
      // media図形
      if(isset($request->figures[0])){
        MediaFigureController::store($media_id, $request);
      }
      // mediaテキスト
      if(isset($request->texts[0])){
        MediaTextController::store($media_id, $request);
      }
      // media音楽
      if(isset($request->audios[0])){
        MediaAudioController::store($media_id, $request);
      }
      // コンテンツ描画エリア
      MediaContentsFieldController::store($media_id, $request->contents_field);
      // media設定
      MediaSettingController::store($media_id, $request);
      // DB::commit();

    } catch(\Exception $e){
      // DB::rollback();
    }
  }

  // media情報を更新
  public static function updateMediaData($media_id, $request){
    DB::beginTransaction();
    try{
      // media画像
      if(isset($request->imgs[0]['id'])){
        MediaImgController::update($media_id, $request->imgs);
      }
      // media図形
      if(isset($request->figures[0])){
        MediaFigureController::update($media_id, $request);
      }      
      // media動画
      if(isset($request->movie['videoId'])){
        MediaMovieController::update($media_id, $request);
      }
      // media音楽
      if(isset($request->audios[0])){
        MediaAudioController::update($media_id, $request);
      }
      // media音楽
      if(isset($request->texts[0])){
        MediaTextController::update($media_id, $request);
      }

      // コンテンツ描画エリア
      MediaContentsFieldController::update($media_id, $request->contents_field);

      // media設定
      MediaSettingController::update($media_id, $request);      

      DB::commit();
    } catch(\Exception $e){
      DB::rollback();
    }

  }


  // media情報をDBから削除
  public static function deleteMediaDataFromDB($media_id){
    $user_id = Auth::user()->id;
    $returnMsg;
    DB::beginTransaction();
    try{
        Media::where('id', $media_id)
            ->where('user_id', $user_id)
            ->first()->delete();
        // Media画像
        if(MediaImg::where('media_id', $media_id)->exists()){
          MediaImgController::destroy($media_id);
        }
        // Medi図形
        if(MediaFigure::where('media_id', $media_id)->exists()){
          MediaFigureController::destroy($media_id);
        }
        // Media音楽
        if(MediaAudio::where('media_id', $media_id)->exists()){                
            MediaAudioController::destroy($media_id);
        }
        // Media動画
        if(MediaMovie::where('media_id', $media_id)->exists()){
            MediaMovieController::destroy($media_id);
        }
        // Mediaテキスト
        if(MediaText::where('media_id', $media_id)->exists()){
          MediaTextController::destroy($media_id);
        }

        // コンテンツ描画エリア
        MediaContentsFieldController::destroy($media_id);

        // Media設定
        MediaSettingController::destroy($media_id);

        DB::commit();
        $returnMsg = 'mediaを削除しました';
    } catch(\Exception $e){
        DB::rollback();
        $returnMsg = 'mediaの削除に失敗しました';
    }

    return $returnMsg;
  }


}