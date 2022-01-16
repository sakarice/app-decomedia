<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Followers;


class FollowUtil
{
  // 自分が対象ユーザをフォローしているか確認する。
  public static function getFollowState($user_id){
    $my_id = Auth::user()->id;
    $isFollow = Followers::where('user_id',$user_id)->where('follower_id',$my_id)->exists();
    return [
      'isFollow' => $isFollow,
    ];
  }
  
  // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
  public static function updateFollowState(Request $request){
      $isFollow = $request->isFollow;
      $follower_id = Auth::user()->id;
      $user_id = $request->user_id;
      $updatedState;
      switch ($isFollow) {
        case false:   // フォロー
          $followers = new Followers();
          $model = $followers->create([
            'user_id' => $user_id,
            'follower_id' => $follower_id,
          ]);
          if($model){$updatedState = true;}
          break;
        case true: // フォロー解除
          Followers::where('user_id', $user_id)
          ->where('follower_id', $follower_id)
          ->first()
          ->delete();
          $updatedState = false;
          break;
      }

      return [
        'isFollow' => $updatedState,
      ];
  }



}