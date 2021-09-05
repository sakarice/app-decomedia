<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Controllers\RoomController;
use App\Lib\EditRoom;
use App\Lib\RoomUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;

class SearchUtil
{
    //
    public static function searchRooms(Request $request){
        $keyword = $request->input('keyword');
        $roomPreviewInfos = array();
        if(!empty($keyword)){
            $room_settings = RoomSetting::where('name', 'LIKE', "%$keyword%")->get();
        }
        foreach($room_settings as $room_setting){
            $room_id = $room_setting->room_id;
            $roomPreviewInfos[] = RoomUtil::getRoomPreviewInfo($room_id);
        }

        $data = [
            'isLogin' => Auth::check(),
            'keyword' => $keyword,
            'roomPreviewInfos' => $roomPreviewInfos
        ];

        return view('searchResult.view', $data);
    }
}
