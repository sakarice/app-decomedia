<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\saveFile;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\DefaultImg;
use App\Models\RoomImg;
use Storage;

class ImgController extends Controller
{
    // room画像の情報をDBから取得し、配列を返す。
    public static function getRoomImgData($room_id){

        $room_img = RoomImg::where('room_id', $room_id)->first();

        $room_img_id = $room_img->img_id;
        $room_img_type = $room_img->img_type;
        $room_img_url;
        if($room_img_type == 1){
            $room_img_url = DefaultImg::where('id', $room_img_id)->first()->img_url;
        }else if($room_img_type == 2){
            $room_img_url = UserOwnImg::where('id', $room_img_id)->first()->img_url;
        }
        $room_img_width = $room_img->img_width;
        $room_img_height = $room_img->img_height;
        $room_img_layer = $room_img->img_layer;

        // room画像情報を格納した連想配列を作成
        $room_img_data = [
            'id' => $room_img_id,
            'type' => $room_img_type,
            'url' => $room_img_url,
            'width' => $room_img_width,
            'height' => $room_img_height,
            'layer' => $room_img_layer,
        ];

        return $room_img_data;

    }
}
