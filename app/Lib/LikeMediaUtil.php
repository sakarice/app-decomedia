<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Media;
use App\Models\UserLikeMedia;


class LikeMediaUtil
{
  
    // 自分が入ったMediaの作成者をフォローしているか確認する。
    public static function getLikeState($media_id){
      $user_id = Auth::user()->id;
      $isLike = UserLikeMedia::where('user_id',$user_id)->where('media_id',$media_id)->exists();
      return [
        'isLike' => $isLike,
      ];
    }

  // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
  public static function updateLikeState(Request $request){
      $isLike = $request->isLike;
      $media_id = $request->media_id;
      $user_id = Auth::user()->id;
      $returnMsg;

      switch ($isLike) {
        case true:   // いいねされたら、いいね情報を作成
          $user_like_media = new UserLikeMedia();
          $user_like_media->user_id = $user_id;
          $user_like_media->media_id = $media_id;
          $user_like_media->save();
          $returnMsg = 'いいねしました';
          break;
        case false: // いいね解除されたら、いいね情報を削除
          UserLikeMedia::where('user_id', $user_id)
          ->where('media_id', $media_id)
          ->first()
          ->delete();
          $returnMsg = 'いいね解除しました';
          break;
      }

      return [
        'msg' => $returnMsg,
      ];
  }



}