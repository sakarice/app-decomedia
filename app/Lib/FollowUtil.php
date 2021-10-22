<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\Followers;


class FollowUtil
{
  // 自分が入ったRoomの作成者をフォローしているか確認する。
  public static function getFollowState($room_owner_id){
    $user_id = Auth::user()->id;
    $isFollow = Followers::where('user_id',$user_id)->where('follower_id',$room_owner_id)->exists();
    return [
      'isFollow' => $isFollow,
    ];
  }
  
  // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
  public static function updateFollowState(Request $request){
      $isFollow = $request->isFollow;
      $follower_id = $request->target_user_id;
      $user_id = Auth::user()->id;
      $returnMsg;
      
      switch ($isFollow) {
        case true:   // フォローされたら、フォロー情報を作成
          $followers = new Followers();
          $followers->user_id = $user_id;
          $followers->follower_id = $follower_id;
          $followers->save();
          $returnMsg = 'フォローしました';
          break;
        case false: // フォロー解除されたら、フォロー情報を削除
          Followers::where('user_id', $user_id)
          ->where('follower_id', $follower_id)
          ->first()
          ->delete();
          $returnMsg = 'フォロー解除しました';
          break;
      }

      return [
        'msg' => $returnMsg,
      ];
  }



}