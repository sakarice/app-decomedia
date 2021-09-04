<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use Storage;

class UserUtil
{

  // ログインユーザ自身のユーザ情報を取得
  public static function getOwnProfile(){
    $user_id = Auth::user()->id;
    $ownProfileData = UserUtil::getUserData($user_id);
    return $ownProfileData;
  }


  // room所有者(=作成者)のユーザ情報を取得
  public static function getRoomOwnerData($room_id){
    $user_id = Room::find($room_id)->user_id;
    $roomOwnerData = UserUtil::getUserData($user_id);
    return $roomOwnerData;
  }
  
  // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
  public static function getUserData($user_id){
    $user_id = Auth::user()->id;
    $user = User::find($user_id);
    $user_data = [
      'id' => $user_id,
      'name' => $user->name,
      'aboutMe' => $user->profile,
    ];
    return $user_data;
  }


}