<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\RoomSettingUtil;
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
      RoomSettingUtil::saveRoomSettingData($room_id, $request);
    }

    // 4.show
    public static function show($room_id){
        $room_setting_data = RoomSettingUtil::getRoomSettingData($room_id);
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
