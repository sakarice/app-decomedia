<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\saveFile;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;
use Storage;

class RoomSettingController extends Controller
{
    //
    public static function getRoomSettingData($room_id){
        $room_setting = RoomSetting::where('room_id', $room_id)->first();

        // room画像情報を格納した連想配列を作成
        $room_setting_data = [
            'name' => $room_setting->name,
            'is_show_img' => $room_setting->is_show_img,
            'is_show_movie' => $room_setting->is_show_movie,
            'max_audio_num' => $room_setting->max_audio_num,
            'background_type' => $room_setting->background_type,
            'background_color' => $room_setting->background_color,
            'chat_valid_flag' => $room_setting->chat_valid_flag,
            'open_state' => $room_setting->open_state,
            'enter_limit' => $room_setting->enter_limit,
        ];

        return $room_setting_data;

    }
}
