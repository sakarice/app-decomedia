<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Track;
use App\Models\Room;
use Storage;

class EditRoom
{
  
  // Room情報を取得(id,title,サムネ画像のurl)
  public static function getUserRoomInfo($limit=10){
      // Room情報を取得
      $authenticated_userId = Auth::user()->id;
      $rooms = Room::limit($limit)->where('user_id', $authenticated_userId)->get();
      $roomInfos = array();
      foreach($rooms as $room){
          $roomInfo = array(
              'id' => $room->id,
              'title' => $room->title,
              'url' => $room->thumbnail_url
          );
          $roomInfos[] = $roomInfo;
      }
      
      return($roomInfos);
  }


}