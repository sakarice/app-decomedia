<?php

namespace App\Http\Controllers\RoomSetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;
use Storage;

class RoomSettingController extends Controller
{
    // 1.index
    public function index(){}
    // 2.create
    public function create(Request $request){}

    // 3.store
    public static function store($room_id, $request){
        \Log::info('Room設定保存開始');
        $user_id = Auth::user()->id;
        $roomSetting = new RoomSetting();
        $roomSetting->room_id = $room_id;
        if(isset($request->setting['name'])){
          $roomSetting->name = $request->setting['name'];
        }else {
          $roomSetting->name = 'room';
        }
        $roomSetting->description = $request->setting['description'];
        $roomSetting->is_show_img = $request->setting['isShowImg'];
        $roomSetting->is_show_movie = $request->setting['isShowMovie'];
        $roomSetting->max_audio_num = $request->setting['maxAudioNum'];
        // $roomSetting->background_type = $request->setting['roomBackgroundType'];
        $roomSetting->background_color = $request->setting['roomBackgroundColor'];
        $roomSetting->save();
        \Log::info('Room設定保存完了orスルー');
    }

    // 4.show
    public static function show($room_id){
        $room_setting = RoomSetting::where('room_id', $room_id)->first();

        // room画像情報を格納した連想配列を作成
        $room_setting_data = [
            'id' => $room_id,
            'name' => $room_setting->name,
            'description' => $room_setting->description,
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

    // 5.edit
    public function edit($id){}
    // 6.update
    public function update($id){}
    // 7.destroy
    public static function destroy($room_id){
      RoomSetting::where('room_id', $room_id)->first()->delete();
    }

}
