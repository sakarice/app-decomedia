<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\Audio\AudioController;
use App\Http\Controllers\RoomImg\RoomImgController;
use App\Http\Controllers\RoomAudio\RoomAudioController;
use App\Http\Controllers\RoomMovie\RoomMovieController;
use App\Http\Controllers\RoomSetting\RoomSettingController;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\UserOwnBgm;
use App\Models\DefaultImg;
use App\Models\DefaultBgm;
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;
use Storage;

class RoomUtil
{

  // 指定のroom情報を取得
  public static function getRoomDatas($room_id){
    // room画像情報をDBから取得
    $room_img_data = RoomImgController::show($room_id);

    // room音楽情報をDBから取得
    $room_audios_data = RoomAudioController::show($room_id);

    // room動画情報をDBから取得
    $room_movie_data = RoomMovieController::show($room_id);

    // room設定情報をDBから取得
    $room_setting_data = RoomSettingController::show($room_id);

    $data = [
        'room_img' => $room_img_data,
        'room_audios' => $room_audios_data,
        'room_movie' => $room_movie_data,
        'room_setting' => $room_setting_data,
    ];

    return $data;

  }


    // Roomのプレビュー表示に必要な情報を取得(id,title,サムネ画像のurl)
    public static function getRoomPreviewInfo($room_id){
      $room_name = RoomSetting::where('room_id', $room_id)->first()->name;
      if(RoomImg::where('room_id', $room_id)->exists()){
          $room_img_id = RoomImg::where('room_id', $room_id)->first()->img_id;
          $room_img_type = RoomImg::where('room_id', $room_id)->first()->img_type;
          $room_img;
          if($room_img_type == 1){
          $room_img = DefaultImg::where('id', $room_img_id)->first();
          } else if ($room_img_type == 2){
          $room_img = UserOwnImg::where('id', $room_img_id)->first();
          }
          $room_img_url = $room_img->img_url;
      } else if(RoomMovie::where('room_id', $room_id)->exists()) {
          // youtubeアイコンの画像URLをセット
          $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/3oLdT6SSOkEUW0ejXRWsLX177aXQVOd5vRa8Qtse.png";
      } else if(RoomBgm::where('room_id', $room_id)->exists()){
          // 音符アイコンの画像をセット
          $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/t6xoK6A2Wgy33J82wCzEvW12pnLqmeDkF4ASzqtO.jpg";
      } else {
          // empty画像をセット
          $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/tyOKqvszOb4LDP2egK6qTqWFzFiFnxlCurxaf98W.png"; 
      }

      $roomInfo = array(
          'id' => $room_id,
          'name' => $room_name,
          'preview_img_url' => $room_img_url,
      );
      return $roomInfo;
    }
    


  // room情報をDBに保存
  public static function saveRoomDataInDB($request){
    DB::beginTransaction();
    try{
      $user_id = Auth::user()->id;
      // room保存
      $room = new Room(); 
      $room->user_id = $user_id;
      $room->save();
      
      // 保存したroomのidを取得
      $room_id = Room::latest()->first()->id;
      \Log::info($room_id);
  
      // room画像
      if(isset($request->img['id'])){
        if($request->img['id'] !== ""){
          RoomImgController::store($room_id, $request);
        }
      }
  
      // room動画
      if(isset($request->movie['videoId'])){
        RoomMovieController::store($room_id, $request);
      }
  
      // room音楽
      if(isset($request->audios[0])){
        RoomAudioController::store($room_id, $request);
      }

      // room設定
      RoomSettingController::store($room_id, $request);

      DB::commit();

    } catch(\Exception $e){
      DB::rollback();
    }
  }

  // room情報をDBから削除
  public static function deleteRoomDataFromDB($room_id){
    $user_id = Auth::user()->id;
    $returnMsg;
    
    DB::beginTransaction();
    try{
        // Room
        Room::where('id', $room_id)
            ->where('user_id', $user_id)
            ->first()
            ->delete();
            // Room画像
        if(RoomImg::where('room_id', $room_id)->exists()){
            RoomImgController::destroy($room_id);
        }
        // Room音楽
        if(RoomBgm::where('room_id', $room_id)->exists()){                
            RoomAudioController::destroy($room_id);
        }
        // Room動画
        if(RoomMovie::where('room_id', $room_id)->exists()){
            RoomMovieController::destroy($room_id);
        }
    // Room設定
        RoomSettingController::destroy($room_id);

        DB::commit();
        $returnMsg = 'roomを削除しました';
    } catch(\Exception $e){
        DB::rollback();
        $returnMsg = 'roomの削除に失敗しました';
    }

    return $returnMsg;

  }


}