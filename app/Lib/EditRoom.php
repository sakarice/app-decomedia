<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DefaultBgm;
use App\Models\DefaultImg;
use App\Models\UserOwnBgm;
use App\Models\UserOwnImg;
use App\Models\Room;
use App\Models\RoomBgm;
use App\Models\RoomImg;
use App\Models\Roomlist;
use App\Models\RoomRoomlist;
use Storage;

class EditRoom
{
  
  // Room情報を取得(id,title,サムネ画像のurl)
  public static function getMyRooms($limit=10){
      // Room情報を取得
      $authenticated_userId = Auth::user()->id;
      $rooms = Room::limit($limit)->where('user_id', $authenticated_userId)->get();

    //   $room_imgs = $rooms->first()->room_imgs;
    //   \Log::info($room_imgs->first()->id);
      $roomInfos = array();
      foreach($rooms as $room){
          $room_id = $room->id;
          $room_img_id = RoomImg::where('room_id', $room_id)->first()->img_id;
          $room_img_type = RoomImg::where('room_id', $room_id)->first()->img_type;
          $room_img;
          if($room_img_type == 1){
            $room_img = DefaultImg::where('id', $room_img_id)->first();
          } else if ($room_img_type == 2){
            $room_img = UserOwnImg::where('id', $room_img_id)->first();
          }
          $room_img_url = $room_img->img_url;

          $roomInfo = array(
              'id' => $room->id,
              'name' => $room->name,
              'img_url' => $room_img_url,
          );

          $roomInfos[] = $roomInfo;
      }
      
      return($roomInfos);
  }


}