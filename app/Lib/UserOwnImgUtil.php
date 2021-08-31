<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\RoomImg\RoomImgController;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\RoomImg;
use Storage;

class UserOwnImgUtil
{

  // 【概要】画像がユーザ自身で登録したものか判別する
  // 【目的】クライアントから送信された画像id改竄によって、他ユーザの画像が使われることを防ぐ
  public static function judgeIsOwnImg($img_id){
    $isOwnImg = false;
    $target_user_id = Auth::user()->id;
    $target_user_own_imgs = UserOwnImg::where('owner_user_id',$target_user_id)->get();
    // 対象ユーザの所持する画像id(全量)の中で、指定された画像idと一致するものがあるかチェック
    foreach($target_user_own_imgs as $target_user_own_img){
      if($target_user_own_img->id == $img_id){
        $isOwnImg = true;
      }
    }
    if( !$isOwnImg ){
      \Log::warning("この画像は他ユーザの所有物か、そもそもDBに存在しません。ユーザによる改竄、もしくはプログラムのバグを疑ってください");
    }

    return $isOwnImg;
  }


}