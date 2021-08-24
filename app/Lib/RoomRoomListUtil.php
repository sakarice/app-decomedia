<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\RoomUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\Roomlist;
use App\Models\RoomRoomlist;
use Storage;

class RoomRoomListUtil
{

  // room - roomリスト情報をDBから削除
  public static function deleteRoomRoomListDataFromDB($room_list_id){
    RoomRoomlist::where('roomlist_id', $room_list_id)
    ->first()
    ->delete();  
  }

}