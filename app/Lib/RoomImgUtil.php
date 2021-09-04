<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RooImgController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomImg;
use App\Models\DefaultImg;
use App\Models\UserOwnImg;
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

  // 4.show 
  // Room画像の情報を取得(Room作成、編集、閲覧時に使用)
  public static function getRoomImgData($room_id){
    $room_img = RoomImg::where('room_id', $room_id)->first();
    $room_img_data = [
        'id' => $room_img->img_id,
        'type' => $room_img->img_type,
        'url' => RoomImgUtil::getRoomImgModel($room_img->img_id, $room_img->img_type)->img_url,
        'width' => $room_img->width,
        'height' => $room_img->height,
        'opacity' => $room_img->opacity,
        'layer' => $room_img->img_layer,
    ];
    return $room_img_data;
  }


  // Room画像のModelを取得
  // タイプに応じて取得先DBを選択
  public static function getRoomImgModel($img_id, $img_type){
    $room_img_model;
    switch ($img_type){
      case 1: // デフォルト画像
        $room_img_model = DefaultImg::where('id', $img_id)->first();
        break;
      case 2: // ユーザがアップロードした画像
        $room_img_model = UserOwnImg::where('id', $img_id)->first();
        break;
    }
    return $room_img_model;
  }


}