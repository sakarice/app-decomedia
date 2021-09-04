<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RoomSettingController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;
use Storage;

class RoomSettingUtil
{

  // 3.store // Room設定情報をDBに保存
  public static function saveRoomSettingData($room_id, $request){
    $user_id = Auth::user()->id;
    $roomSetting = new RoomSetting();
    $roomSetting->room_id = $room_id;
    $roomSetting->open_state = $request->setting['isPublic'];
    if(isset($request->setting['name'])){
      $roomSetting->name = $request->setting['name'];
    }else {
      $roomSetting->name = 'room';
    }
    $roomSetting->description = $request->setting['description'];
    $roomSetting->finish_time = $request->setting['finish_time'];
    $roomSetting->is_show_img = $request->setting['isShowImg'];
    $roomSetting->is_show_movie = $request->setting['isShowMovie'];
    $roomSetting->max_audio_num = $request->setting['maxAudioNum'];
    // $roomSetting->background_type = $request->setting['roomBackgroundType'];
    $roomSetting->background_color = $request->setting['roomBackgroundColor'];
    $roomSetting->save();
  }

  // 4. show // Room設定情報を取得
  public static function getRoomSettingData($room_id){
    $room_setting = RoomSetting::where('room_id', $room_id)->first();
    $room_setting_data = [
        'id' => $room_id,
        'name' => $room_setting->name,
        'description' => $room_setting->description,
        'finish_time' => $room_setting->finish_time,
        'is_show_img' => $room_setting->is_show_img,
        'is_show_movie' => $room_setting->is_show_movie,
        'max_audio_num' => $room_setting->max_audio_num,
        'background_type' => $room_setting->background_type,
        'background_color' => $room_setting->background_color,
        'chat_valid_flag' => $room_setting->chat_valid_flag,
        'isPublic' => $room_setting->open_state,
        'enter_limit' => $room_setting->enter_limit,
    ];
    return $room_setting_data;
  }


}