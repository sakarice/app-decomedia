<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RooAudioController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\MediaAudio;
use App\Models\PublicAudio;
use App\Models\UserOwnAudio;
use Storage;

class MediaAudioUtil
{

  // 3.store // Media画像情報をDBに保存
  public static function saveMediaAudioData($media_id, $request){
    $req_media_audios = $request->audios;
    foreach($req_media_audios as $index => $req_media_audio){
      $mediaAudio = new MediaAudio();
      $mediaAudio->media_id = $media_id;
      $mediaAudio->audio_type = $req_media_audio['type'];
      $mediaAudio->audio_id = MediaAudioUtil::getAudioId($req_media_audio['type'], $req_media_audio['audio_url']);
      $mediaAudio->order_seq = $index + 1;
      $mediaAudio->volume = $req_media_audio['volume'];
      $mediaAudio->isLoop = $req_media_audio['isLoop'];
      if($req_media_audio['type'] == 2){ //2:ユーザのアップロードした音楽
          $mediaAudio->owner_user_id = Auth::user()->id;
      } // 2以外はpublicオーディオ(=ユーザのものでない）ので、NULLで良い
      \Log::info('called saveMediaAudioData');
      $mediaAudio->save();
    }

  }

  // 4.show 
  // Media画像の情報を取得(Media作成、編集、閲覧時に使用)
  public static function getMediaAudioData($media_id){
    $media_audio_data = array();
    if(MediaAudio::where('media_id', $media_id)->exists()){
      $media_audios = MediaAudio::where('media_id', $media_id)->get();

      // Media音楽の情報を連想配列として一つずつ取得し、配列に追加していく
      foreach($media_audios as $media_audio){
        $audio_model;
        if($media_audio->audio_type == 1){
          $audio_model = PublicAudio::where('id', $media_audio->audio_id)->first();
        } else if($media_audio->audio_type == 2) {
          $audio_model = UserOwnAudio::where('id', $media_audio->audio_id)->first();
        }
        $tmp_media_audio_data = [
          'id' => $media_audio->audio_id,
          'type' => $media_audio->audio_type,
          'name' => $audio_model->name,
          'audio_url' => $audio_model->audio_url,
          'thumbnail_url' => $audio_model->thumbnail_url,
          'volume' => $media_audio->volume,
          'isLoop' => $media_audio->isLoop,
        ];
        $media_audio_data[] = $tmp_media_audio_data;
      }

    }
    return $media_audio_data;
  }

  // 【関数】MediaAudioのレコード数をリクエストのAudio数と同じにする
  public static function equalizeNumOfMediaAudioDataWithRequest($media_id, $request_num){
    // 更新前のAudio数とリクエストされたAudio数を比較
    $audioNumBeforeUpdate = MediaAudio::where('media_id', $media_id)->count();
    $audioNumDiff = $request_num - $audioNumBeforeUpdate;
    if($audioNumDiff > 0){
      // 足りない分だけ空のレコードを追加
      for($i=0; $i<$audioNumDiff; $i++){
        MediaAudioUtil::addEmptyMediaAudioData($media_id);
      }
    } else if($audioNumDiff < 0){
      // 多い分だけレコードを削除
      for($i=0; $i<abs($audioNumDiff); $i++){
        MediaAudio::where('media_id', $media_id)->orderBy('id', 'desc')->first()->delete();
      }
    }
  }


  // 6.update
  public static function updateMediaAudioData($media_id, $request){
    \Log::info('start updateMediaAudioData');
    $req_media_audios;
    $requestAudioNum;
    if(isset($request->audios[0])){ // リクエストにaudioがセットされている場合
      $req_media_audios = $request->audios;
      $requestAudioNum = count($req_media_audios);
      // DBのレコード数をリクエストのAudio数と同じにする
      MediaAudioUtil::equalizeNumOfMediaAudioDataWithRequest($media_id, $requestAudioNum);
      // レコードを更新
      \Log::info('レコードを更新');
      $mediaAudios = MediaAudio::where('media_id', $media_id)->orderBy('id')->get();
      \Log::info($mediaAudios);
      \Log::info($req_media_audios);
      foreach($req_media_audios as $index => $req_media_audio){
        $mediaAudios[$index]->audio_type = $req_media_audio['type'];
        $mediaAudios[$index]->audio_id = MediaAudioUtil::getAudioId($req_media_audio['type'], $req_media_audio['audio_url']);
        $mediaAudios[$index]->order_seq = $index + 1;
        $mediaAudios[$index]->volume = $req_media_audio['volume'];
        $mediaAudios[$index]->isLoop = $req_media_audio['isLoop'];
        if($req_media_audio['type'] == 1){ //1:デフォルト 2:ユーザのアップロードしたもの
          $mediaAudios[$index]->owner_user_id = NULL;
        } else if($req_media_audio['type'] == 2){
          $mediaAudios[$index]->owner_user_id = Auth::user()->id;
        }
        $mediaAudios[$index]->save();
      }
    } else { // セットされていなかった場合
      $requestAudioNum = 0;
      // DBのMediaAudioのレコードを削除。更新処理は行わない。
      MediaAudioUtil::equalizeNumOfMediaAudioDataWithRequest($media_id, $requestAudioNum);
    }

  }


  // 空のAudioレコードを作成する。
  public static function addEmptyMediaAudioData($media_id){
      $mediaAudio = new MediaAudio();
      $mediaAudio->media_id = $media_id;
      $mediaAudio->audio_type = 0;
      $mediaAudio->audio_id = 0;
      $mediaAudio->order_seq = -1;
      $mediaAudio->volume = 1;
      $mediaAudio->isLoop = false;
      $mediaAudio->owner_user_id = Auth::user()->id;
      $mediaAudio->save();
  }


  // 種別と保存先URLからオーディオのidを取得
  public static function getAudioId($type, $url){
    $audio_id;
    switch ($type){
      case 1: // デフォルト音楽
        $audio_id = PublicAudio::where('audio_url', $url)->first()->id;
        break;
      case 2: // ユーザがアップロードした音楽
        $audio_id = UserOwnAudio::where('audio_url', $url)->first()->id;
        break;
    }
    return $audio_id;
  }


}