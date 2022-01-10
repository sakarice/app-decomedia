<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaComment;


class MediaCommentUtil
{

  private static $NAME_PAIRS_IN_COLUMN_AND_PROPERTY = array(
    "media_id" => "media_id",
    "user_id" => "user_id",
    "user_name" => "user_name",
    "user_icon_url" => "user_icon_url",
    "comment" => "comment",
  );
  
  // 自分が入ったMediaの作成者をフォローしているか確認する。
  public static function getMediaComments($media_id){
    $user_id = Auth::user()->id;
    $isLike = UserMediaComment::where('user_id',$user_id)->where('media_id',$media_id)->exists();
    return [
      'isLike' => $isLike,
    ];
  }

  public static function store($req_datas){

  }



}