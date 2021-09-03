<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use Storage;

class UserUtil
{
  
  // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
  public static function getProfile(){
      $user_id = Auth::user()->id;
      $user = User::find($user_id);
      $user_name = $user->name;
      $about_me = $user->profile;
      
      return [
        'id' => $user_id,
        'name' => $user_name,
        'aboutMe' => $about_me,
      ];
  }

  // room所有者(=作成者)のユーザ情報を取得
  public static function getRoomOwnerInfo($room_id){
    $user_id = Room::find($room_id)->user_id;
    $user = User::find($user_id);
    $user_name = $user->name;
    $about_me = $user->profile;
    
    return [
      'id' => $user_id,
      'name' => $user_name,
      'aboutMe' => $about_me,
    ];    

  }


}