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
    "profile_img_url" => "profile_img_url",
    "comment" => "comment",
  );
  
  // 自分が入ったMediaの作成者をフォローしているか確認する。
  public static function show($media_id){
    $comments = MediaComment::where('media_id',$media_id)->get();
    $my_user_id = Auth::user()->id;
    foreach($comments as $comment){
      if($comment['user_id'] == $my_user_id){ // 自分のコメントか判定
        $comment['is_my_comment'] = true;
      } else {
        $comment['is_my_comment'] = false;
      }
      $comment['user_id'] = 0; // セキュリティ観点から、ユーザIDはフロントに渡さない
    }
    return ['comments' => $comments];
  }

  public static function store(Request $request){
    $req_data = $request->comment;
    if(Auth::user()->id == $req_data['user_id']){
      $store_items = array();
      $comment = new MediaComment(); 
      foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column => $property){
        $store_items[$column] = $req_data[$property];
      }
      $stored_data = $comment->create($store_items);
      // $stored_data_all = MediaComment::
      $stored_data['is_my_comment'] = true; // 自分のコメントであるという情報を付与
      return $stored_data;
    }
  }

  public static function destroy(Request $request){
    $c = $request->comment;
    $id = $c['id'];
    $media_id = $c['media_id'];
    $target_model = MediaComment::where('id',$id)->where('media_id',$media_id)->first();
    if($target_model){
      $comment_user = $target_model['user_id'];
      if($comment_user != Auth::user()->id){
        return ['message' => "これはあなたのコメントではありません。"];
      } else {
        $target_model->delete();
      }
    }
  }



}