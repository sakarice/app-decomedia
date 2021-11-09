<?php

namespace App\Http\Controllers\Ajax\Lib;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;

use Storage;

class RoomUtilAjax
{
    // 入ったroomが自分の作成したroomか判別する(※ログインしている前提)
    public static function judgeIsMyRoom($room_id){
        $enter_user_id = Auth::user()->id;
        $room_owner_user_id = Room::find($room_id)->user_id;
        $isMyRoom;

        if($enter_user_id == $room_owner_user_id){
            $isMyRoom = true;
        } else {
            $isMyRoom = false;
        }

        return ['isMyRoom' => $isMyRoom];
    }

}
