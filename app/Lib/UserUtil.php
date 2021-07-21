<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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


}