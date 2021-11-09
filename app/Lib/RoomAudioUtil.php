<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RooAudioController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomAudio;
use App\Models\PublicAudio;
use App\Models\UserOwnAudio;
use Storage;

class RoomAudioUtil
{

  // 3.store // Room画像情報をDBに保存
  public static function saveRoomAudioData($room_id, $request){
    $req_room_audios = $request->audios;
    foreach($req_room_audios as $index => $req_room_audio){
      $roomAudio = new RoomAudio();
      $roomAudio->room_id = $room_id;
      $roomAudio->audio_type = $req_room_audio['type'];
      $roomAudio->audio_id = RoomAudioUtil::getAudioId($req_room_audio['type'], $req_room_audio['audio_url']);
      $roomAudio->order_seq = $index + 1;
      $roomAudio->volume = $req_room_audio['volume'];
      $roomAudio->isLoop = $req_room_audio['isLoop'];
      if($req_room_audio['type'] == 2){ //2:ユーザのアップロードした音楽
          $roomAudio->owner_user_id = Auth::user()->id;
      } // 2以外はpublicオーディオ(=ユーザのものでない）ので、NULLで良い
      $roomAudio->save();
    }

  }

  // 4.show 
  // Room画像の情報を取得(Room作成、編集、閲覧時に使用)
  public static function getRoomAudioData($room_id){
    $room_audio_data = array();
    if(RoomAudio::where('room_id', $room_id)->exists()){
      $room_audios = RoomAudio::where('room_id', $room_id)->get();

      // Room音楽の情報を連想配列として一つずつ取得し、配列に追加していく
      foreach($room_audios as $room_audio){
        $audio_model;
        if($room_audio->audio_type == 1){
          $audio_model = PublicAudio::where('id', $room_audio->audio_id)->first();
        } else if($room_audio->audio_type == 2) {
          $audio_model = UserOwnAudio::where('id', $room_audio->audio_id)->first();
        }
        $tmp_room_audio_data = [
          'id' => $room_audio->audio_id,
          'type' => $room_audio->audio_type,
          'name' => $audio_model->name,
          'audio_url' => $audio_model->audio_url,
          'thumbnail_url' => $audio_model->thumbnail_url,
          'volume' => $room_audio->volume,
          'isLoop' => $room_audio->isLoop,
        ];
        $room_audio_data[] = $tmp_room_audio_data;
      }

    }
    return $room_audio_data;
  }

  // 【関数】RoomAudioのレコード数をリクエストのAudio数と同じにする
  public static function equalizeNumOfRoomAudioDataWithRequest($room_id, $request_num){
    // 更新前のAudio数とリクエストされたAudio数を比較
    $audioNumBeforeUpdate = RoomAudio::where('room_id', $room_id)->count();
    $audioNumDiff = $request_num - $audioNumBeforeUpdate;
    if($audioNumDiff > 0){
      // 足りない分だけ空のレコードを追加
      for($i=0; $i<$audioNumDiff; $i++){
        RoomAudioUtil::addEmptyRoomAudioData($room_id);
      }
    } else if($audioNumDiff < 0){
      // 多い分だけレコードを削除
      for($i=0; $i<abs($audioNumDiff); $i++){
        RoomAudio::where('room_id', $room_id)->orderBy('id', 'desc')->first()->delete();
      }
    }
  }


  // 6.update
  public static function updateRoomAudioData($room_id, $request){
    $roomAudios;
    $requestAudioNum;
    if(isset($request->audios[0])){ // リクエストにaudioがセットされている場合
      $roomAudios = $request->audios;
      $requestAudioNum = count($roomAudios);
      // DBのレコード数をリクエストのAudio数と同じにする
      RoomAudioUtil::equalizeNumOfRoomAudioDataWithRequest($room_id, $requestAudioNum);
      // レコードを更新
      $roomAudios = RoomAudio::where('room_id', $room_id)->orderBy('id')->get();
      foreach($roomAudios as $index => $roomAudio){
        $roomAudios[$index]->audio_type = $roomAudio['type'];
        $roomAudios[$index]->audio_id = RoomAudioUtil::getAudioId($roomAudio['type'], $roomAudio['audio_url']);
        $roomAudios[$index]->order_seq = $index + 1;
        $roomAudios[$index]->volume = $roomAudio['volume'];
        $roomAudios[$index]->isLoop = $roomAudio['isLoop'];
        if($roomAudio['type'] == 1){ //1:デフォルト 2:ユーザのアップロードしたもの
          $roomAudios[$index]->owner_user_id = NULL;
        } else if($roomAudio['type'] == 2){
          $roomAudios[$index]->owner_user_id = Auth::user()->id;
        }
        $roomAudios[$index]->save();
      }
    } else { // セットされていなかった場合
      $requestAudioNum = 0;
      // DBのRoomAudioのレコードを削除。更新処理は行わない。
      RoomAudioUtil::equalizeNumOfRoomAudioDataWithRequest($room_id, $requestAudioNum);
    }

  }


  // 空のAudioレコードを作成する。
  public static function addEmptyRoomAudioData($room_id){
      $roomAudio = new RoomAudio();
      $roomAudio->room_id = $room_id;
      $roomAudio->audio_type = 0;
      $roomAudio->audio_id = 0;
      $roomAudio->order_seq = -1;
      $roomAudio->volume = 1;
      $roomAudio->isLoop = false;
      $roomAudio->owner_user_id = Auth::user()->id;
      $roomAudio->save();
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