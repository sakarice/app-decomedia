<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\UserLikeRoom;


class LikeRoomUtil
{
  
  // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
  public static function updateLikeState(Request $request){
      $user_id = Auth::user()->id;
      $returnMsg;
      if($request->isLike){ // いいねされたら、いいね情報を作成
        $user_like_room = new UserLikeRoom();
        $user_like_room->user_id = $user_id;
        $user_like_room->room_id = $request->room_id;
        $user_like_room->save();
        $returnMsg = 'いいねしました';
      } else if(!($request->isLike)){ // いいね解除されたら、いいね情報を削除
        UserLikeRoom::where('user_id', $user_id)
        ->where('room_id', $request->room_id)
        ->first()
        ->delete();
        $returnMsg = 'いいね解除しました';
      }
      
      return [
        'msg' => $returnMsg,
      ];
  }



}