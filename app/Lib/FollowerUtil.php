<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Followers;


class FollowerUtil
{
  
  public static function show(){
    $user_id = Auth::user()->id;
    $followers_id = Followers::where('user_id',$user_id)->get('follower_id')->pluck('follower_id');
    $followers_info = User::whereIn('id', $followers_id)
    ->get(['id', 'name', 'profile_img_url', 'profile']);
    return ['followers' => $followers_info];
  }

}