<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\RoomSettingController;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\DefaultImg;
use App\Models\RoomImg;
use App\Models\RoomSetting;
use Storage;

class ImgUtil
{

    // 画像タイプに応じたテーブルからRoom画像のURLを取得
    public static function getRoomImgUrlByType($room_img_id, $img_type){
        switch ($img_type) {
            case 1: // Default画像
                $room_img_url = DefaultImg::where('id', $room_img_id)->first()->img_url;                
                break;
            case 2: // ユーザアップロードした画像
                $room_img_url = UserOwnImg::where('id', $room_img_id)->first()->img_url;
                break;
        }

        return $room_img_url;
    }
    
    // // room画像の情報をDBから取得し、配列を返す。
    // public static function getRoomImgData($room_id){
    //     // 〇 room画像が設定されている場合⇒DBから情報取得
    //     if(RoomImg::where('room_id', $room_id)->exists()){
    //         $room_img = RoomImg::where('room_id', $room_id)->first();
    //         $room_img_id = $room_img->img_id;
    //         $room_img_type = $room_img->img_type;
    //         $room_img_url;
    //         if($room_img_type == 1){
    //             $room_img_url = DefaultImg::where('id', $room_img_id)->first()->img_url;
    //         }else if($room_img_type == 2){
    //             $room_img_url = UserOwnImg::where('id', $room_img_id)->first()->img_url;
    //         }
    //         $room_img_width = $room_img->width;
    //         $room_img_height = $room_img->height;
    //         $room_img_layer = $room_img->img_layer;
    //     // × room画像が設定されていない場合⇒デフォルト値をセット
    //     } else {            
    //         $room_img_id
    //         = $room_img_type
    //         = $room_img_width
    //         = $room_img_height
    //         = $room_img_layer
    //         = 0;
    //         $room_img_url = "";
    //     }

    //     // room画像情報を格納した連想配列を作成
    //     $room_img_data = [
    //         'id' => $room_img_id,
    //         'type' => $room_img_type,
    //         'url' => $room_img_url,
    //         'width' => $room_img_width,
    //         'height' => $room_img_height,
    //         'layer' => $room_img_layer,
    //     ];

    //     return $room_img_data;

    // }



}