<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Controllers\RoomController;
use App\Lib\RoomUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;

class SearchUtil
{
    // 検索結果に表示するroomの最大数
    private static $maxGetNum = 20;

    public static function searchRooms(Request $request){
        $keyword = $request->input('keyword');
        \Log::info($request);
        \Log::info($request->has('input'));
        \Log::info($request->has('keyword'));
        \Log::info($keyword);
        $roomPreviewInfos = array();
        if(!empty($keyword)){
            $room_settings
             = RoomSetting::where('open_state', true)
                ->where('name', 'LIKE', "%$keyword%")
                ->take(self::$maxGetNum)
                ->get();
        } else {
            $room_settings = RoomSetting::where('open_state', true)
                ->inRandomOrder()
                ->take(self::$maxGetNum)
                ->get();
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

        return view('search.view', $data);
    }
}
