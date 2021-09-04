<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\RoomImgController;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\DefaultImg;
use App\Models\RoomImg;
use Storage;

class DefaultImgUtil
{

  // 【概要】画像がユーザ自身で登録したものか判別する
  // 【目的】クライアントから送信された画像id改竄によって、他ユーザの画像が使われることを防ぐ
  public static function judgeIsDefaultImg($img_id){
    $isDefaultImg = false;
    $default_imgs = DefaultImg::all();
    // デフォルト画像の中で、指定された画像と一致するものがあるかチェック
    foreach($default_imgs as $default_img){
      if($default_img->id == $img_id){
        $isDefaultImg = true;
      }
    }

    return $isDefaultImg;
  }


}