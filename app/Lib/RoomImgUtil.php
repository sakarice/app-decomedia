<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\RoomImg\RoomImgController;
use App\Http\Controllers\RoomSetting\RoomSettingController;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\DefaultImg;
use App\Models\RoomImg;
use App\Models\RoomSetting;
use Storage;

class RoomImgUtil
{

  // 3.store // Room画像情報をDBに保存
  public static function saveRoomImgData($room_id, $request){
    $roomImg = new RoomImg();
    $roomImg->room_id = $room_id;
    $roomImg->owner_user_id = Auth::user()->id;
    $roomImg->img_id = $request->img['id'];
    $roomImg->img_type = $request->img['type'];
    $roomImg->width = $request->img['width'];
    $roomImg->height = $request->img['height'];
    $roomImg->opacity = $request->img['opacity'];
    $roomImg->img_layer = $request->img['layer'];
    $roomImg->save();
  }

  // 4.show   // Room画像情報の連想配列を返す
  public static function getRoomImgData($room_id){
    $room_img = RoomImg::where('room_id', $room_id)->first();
    $room_img_data = [
        'id' => $room_img->img_id,
        'type' => $room_img->img_type,
        'url' => ImgUtil::getRoomImgUrlByType($room_img->img_id, $room_img->img_type),
        'width' => $room_img->width,
        'height' => $room_img->height,
        'opacity' => $room_img->opacity,
        'layer' => $room_img->img_layer,
    ];
    return $room_img_data;
  }


}