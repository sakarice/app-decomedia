<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\RoomAudioUtil;
use App\Lib\storeFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnBgm;
use App\Models\DefaultBgm;
use App\Models\RoomBgm;
use Storage;


class RoomAudioController extends Controller
{
        // 1.index
        public static function index(){}
        // 2.create
        public static function create(Request $request){}
        // 3.store
        public static function store($room_id, $request){
            RoomAudioUtil::saveRoomAudioData($room_id, $request);
        }

        // 4.show
        public static function show($room_id){
            $room_bgm_data = RoomAudioUtil::getRoomAudioData($room_id);    
            return $room_bgm_data;
        }

        // 5.edit
        public static function edit($room_id){}
        // 6.update
        public static function update($room_id, $request){
            RoomAudioUtil::updateRoomAudioData($room_id, $request);
        }
        // 7.destroy
        public static function destroy($room_id){
            RoomBgm::where('room_id', $room_id)->delete();
        }
    
}
