<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RooAudioController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomBgm;
use App\Models\DefaultBgm;
use App\Models\UserOwnBgm;
use Storage;

class RoomAudioUtil
{

  // 3.store // Room画像情報をDBに保存
  public static function saveRoomAudioData($room_id, $request){
    $roomAudios = $request->audios;
    foreach($roomAudios as $index => $roomAudio){
      $roomBgm = new RoomBgm();
      $roomBgm->room_id = $room_id;
      $roomBgm->audio_type = $roomAudio['type'];
      $roomBgm->audio_id = RoomAudioUtil::getAudioId($roomAudio['type'], $roomAudio['audio_url']);
      $roomBgm->order_seq = $index + 1;
      $roomBgm->volume = $roomAudio['volume'];
      $roomBgm->isLoop = $roomAudio['isLoop'];
      if($roomAudio['type'] == 2){ //2:ユーザのアップロードした音楽
          $roomBgm->owner_user_id = Auth::user()->id;
      } // 2以外はdefaultのオーディオ(=ユーザのものでない）ので、NULLで良い
      $roomBgm->save();
    }

  }

  // 4.show 
  // Room画像の情報を取得(Room作成、編集、閲覧時に使用)
  public static function getRoomAudioData($room_id){
    $room_bgm_data = array();
    if(RoomBgm::where('room_id', $room_id)->exists()){
      $room_bgms = RoomBgm::where('room_id', $room_id)->get();

      // Room音楽の情報を連想配列として一つずつ取得し、配列に追加していく
      foreach($room_bgms as $room_bgm){
        $bgm_model;
        if($room_bgm->audio_type == 1){
          $bgm_model = DefaultBgm::where('id', $room_bgm->audio_id)->first();
        } else if($room_bgm->audio_type == 2) {
          $bgm_model = UserOwnBgm::where('id', $room_bgm->audio_id)->first();
        }
        $tmp_room_bgm_data = [
          'id' => $room_bgm->audio_id,
          'type' => $room_bgm->audio_type,
          'name' => $bgm_model->name,
          'audio_url' => $bgm_model->audio_url,
          'thumbnail_url' => $bgm_model->thumbnail_url,
          'volume' => $room_bgm->volume,
          'isLoop' => $room_bgm->isLoop,
        ];
        $room_bgm_data[] = $tmp_room_bgm_data;
      }

    }
    return $room_bgm_data;
  }

  // 6.update
  public static function updateRoomAudioData($room_id, $request){
    
    // 【関数】RoomAudioのレコード数をリクエストのAudio数と同じにする
    function equalizeNumOfRoomAudioDataWithRequest($room_id, $request_num){
      // 更新前のAudio数とリクエストされたAudio数を比較
      $audioNumBeforeUpdate = RoomBgm::where('room_id', $room_id)->count();
      $audioNumDiff = $request_num - $audioNumBeforeUpdate;
      if($audioNumDiff > 0){
        // 足りない分だけ空のレコードを追加
        for($i=0; $i<$audioNumDiff; $i++){
          RoomAudioUtil::addEmptyRoomAudioData($room_id);
        }
      } else if($audioNumDiff < 0){
        // 多い分だけレコードを削除
        for($i=0; $i<abs($audioNumDiff); $i++){
          RoomBgm::where('room_id', $room_id)->orderBy('id', 'desc')->first()->delete();
        }
      }
    }

    $roomAudios;
    $requestAudioNum;
    if(isset($request->audios[0])){ // リクエストにaudioがセットされている場合
      $roomAudios = $request->audios;
      $requestAudioNum = count($roomAudios);
      // DBのレコード数をリクエストのAudio数と同じにする
      equalizeNumOfRoomAudioDataWithRequest($room_id, $requestAudioNum);
      // レコードを更新
      $roomBgms = RoomBgm::where('room_id', $room_id)->orderBy('id')->get();
      foreach($roomAudios as $index => $roomAudio){
        $roomBgms[$index]->audio_type = $roomAudio['type'];
        $roomBgms[$index]->audio_id = RoomAudioUtil::getAudioId($roomAudio['type'], $roomAudio['audio_url']);
        $roomBgms[$index]->order_seq = $index + 1;
        $roomBgms[$index]->volume = $roomAudio['volume'];
        $roomBgms[$index]->isLoop = $roomAudio['isLoop'];
        if($roomAudio['type'] == 2){ //2:ユーザのアップロードした音楽
            $roomBgms[$index]->owner_user_id = Auth::user()->id;
        } // 2以外はdefaultのオーディオ(=ユーザのものでない）ので、NULLで良い
        $roomBgms[$index]->save();
      }
    } else { // セットされていなかった場合
      $requestAudioNum = 0;
      // DBのRoomAudioのレコードを削除。更新処理は行わない。
      equalizeNumOfRoomAudioDataWithRequest($room_id, $requestAudioNum);
    }

  }


  // 空のAudioレコードを作成する。
  public static function addEmptyRoomAudioData($room_id){
      $roomBgm = new RoomBgm();
      $roomBgm->room_id = $room_id;
      $roomBgm->audio_type = 0;
      $roomBgm->audio_id = 0;
      $roomBgm->order_seq = -1;
      $roomBgm->volume = 1;
      $roomBgm->isLoop = false;
      $roomBgm->owner_user_id = Auth::user()->id;
      $roomBgm->save();
  }


  // 種別と保存先URLからオーディオのidを取得
  public static function getAudioId($type, $url){
    $audio_id;
    switch ($type){
      case 1: // デフォルト音楽
        $audio_id = DefaultBgm::where('audio_url', $url)->first()->id;
        break;
      case 2: // ユーザがアップロードした音楽
        $audio_id = UserOwnBgm::where('audio_url', $url)->first()->id;
        break;
    }
    return $audio_id;
  }


}