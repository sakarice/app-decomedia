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
        \Log::info($room_img_id);
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



}