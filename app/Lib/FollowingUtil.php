<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Followers;


class FollowingUtil
{
  
  public static function show(){
    $user_id = Auth::user()->id;
    $followings_id = Followers::where('follower_id',$user_id)->get('user_id')->pluck('user_id');
    $followings_info = User::whereIn('id', $followings_id)
    ->get(['id', 'name', 'profile_img_url', 'profile']);
    return ['followings' => $followings_info];
  }

}