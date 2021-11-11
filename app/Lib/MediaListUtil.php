<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\MediaImgController;
use App\Http\Controllers\MediaAudioController;
use App\Http\Controllers\MediaMovieController;
use App\Http\Controllers\MediaSettingController;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Lib\MediaUtil;
use App\Models\User;
use App\Models\Media;
use App\Models\Medialist;
use App\Models\MediaMedialist;

use Storage;

class MediaListUtil
{

  // 指定のmedia情報を取得
  public static function getMediaDatas($media_id){
    // media画像情報をDBから取得
    $media_img_data = MediaImgController::show($media_id);

    // media音楽情報をDBから取得
    $media_audios_data = MediaAudioController::show($media_id);

    // media動画情報をDBから取得
    $media_movie_data = MediaMovieController::show($media_id);

    // media設定情報をDBから取得
    $media_setting_data = MediaSettingController::show($media_id);

    $data = [
        'media_img' => $media_img_data,
        'media_audios' => $media_audios_data,
        'media_movie' => $media_movie_data,
        'media_setting' => $media_setting_data,
    ];

    return $data;

  }

  // 作成済みmediaのプレビュー情報を取得
  public static function getCreatedMediaPreviewInfos($record_num=100){
    $authenticated_userId = Auth::user()->id;
    $createdMediaPreviewInfos = array();
    $medias = Media::limit($record_num)->where('user_id', $authenticated_userId)->get();
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
        INNER JOIN user_like_medias ulr
        ON ulr.media_id = r.id
        WHERE ulr.user_id = $user_id
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
    public static function getMediaListPreviewInfo($media_list_id){
      $name = Medialist::find($media_list_id)->name;
      $thumbnail_img_url = Medialist::find($media_list_id)->thumbnail_img_url;

      $mediaListInfo = array(
          'id' => $media_list_id,
          'name' => $name,
          'preview_img_url' => $thumbnail_img_url,
      );
      return $mediaListInfo;
    }
    


  // media情報をDBに保存
  public static function saveMediaDataInDB($request){
    DB::beginTransaction();
    try{
      $user_id = Auth::user()->id;
      // media保存
      $media = new Media(); 
      $media->user_id = $user_id;
      $media->save();
      
      // 保存したmediaのidを取得
      $media_id = Media::latest()->first()->id;

      // media画像
      if(isset($request->img['id'])){
        if($request->img['id'] !== ""){
          MediaImgController::store($media_id, $request);
        }
      }
  
      // media動画
      if(isset($request->movie['videoId'])){
        MediaMovieController::store($media_id, $request);
      }
  
      // media音楽
      if(isset($request->audios[0])){
        MediaAudioController::store($media_id, $request);
      }

      // media設定
      MediaSettingController::store($media_id, $request);

      DB::commit();

    } catch(\Exception $e){
      DB::rollback();
    }
  }


  // media情報をDBから削除
  public static function deleteMediaListDataFromDB($media_list_id){
    $user_id = Auth::user()->id;
    Medialist::where('id', $media_list_id)
      ->where('user_id', $user_id)
      ->first()
      ->delete();
  }


}