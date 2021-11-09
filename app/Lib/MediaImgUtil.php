<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RooImgController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\MediaImg;
use App\Models\PublicImg;
use App\Models\UserOwnImg;
use Storage;

class MediaImgUtil
{

  // 3.store // Media画像情報をDBに保存
  public static function saveMediaImgData($media_id, $request){
    $mediaImg = new MediaImg();
    $mediaImg->media_id = $media_id;
    $mediaImg->owner_user_id = Auth::user()->id;
    $mediaImg->img_id = $request->img['id'];
    $mediaImg->img_type = $request->img['type'];
    $mediaImg->width = $request->img['width'];
    $mediaImg->height = $request->img['height'];
    $mediaImg->opacity = $request->img['opacity'];
    $mediaImg->img_layer = $request->img['layer'];
    $mediaImg->save();
  }

  public static function saveTentativeMediaImgData($media_id){
    $mediaImg = new MediaImg();
    $mediaImg->media_id = $media_id;
    $mediaImg->owner_user_id = Auth::user()->id;
    $mediaImg->img_id = 0;
    $mediaImg->img_type = 0;
    $mediaImg->width = 500;
    $mediaImg->height = 500;
    $mediaImg->opacity = 1;
    $mediaImg->img_layer = 1;
    $mediaImg->save();
    
  }

  // 4.show 
  // Media画像の情報を取得(Media作成、編集、閲覧時に使用)
  public static function getMediaImgData($media_id){
    $media_img_data;
    $img_url = "";
    $media_img = MediaImg::where('media_id', $media_id)->first();
    if($media_img->img_id == 0){
      $media_img_data = MediaImgUtil::getEmptyMediaImgData();
    } else {
      $img_url = MediaImgUtil::getMediaImgModel($media_img->img_id, $media_img->img_type)->img_url;
      $media_img_data = [
          'id' => $media_img->img_id,
          'type' => $media_img->img_type,
          'url' => $img_url,
          'width' => $media_img->width,
          'height' => $media_img->height,
          'opacity' => $media_img->opacity,
          'layer' => $media_img->img_layer,
      ];
    };
    return $media_img_data;
}


  // 6.update
  public static function updateMediaImgData($media_id, $request){
    $mediaImg = MediaImg::where('media_id', $media_id)->first();
    $mediaImg->img_id = $request->img['id'];
    $mediaImg->img_type = $request->img['type'];
    $mediaImg->width = $request->img['width'];
    $mediaImg->height = $request->img['height'];
    $mediaImg->opacity = $request->img['opacity'];
    $mediaImg->img_layer = $request->img['layer'];
    $mediaImg->save();
  }

  public static function updateMediaImgDataToTentative($media_id){
    $mediaImg = MediaImg::where('media_id', $media_id)->first();
    $mediaImg->img_id = 0;
    $mediaImg->img_type = 0;
    $mediaImg->width = 500;
    $mediaImg->height = 500;
    $mediaImg->opacity = 1;
    $mediaImg->img_layer = 1;
    $mediaImg->save(); 
  }


  // 仮のMedia画像情報を作成
  public static function getEmptyMediaImgData(){
    $media_img_data = [
      'id' => "",
      'type' => 0,
      'url' => "",
      'width' => 500,
      'height' => 500,
      'opacity' => 1,
      'layer' => 1,
    ];
    return $media_img_data;  
  }


  // Media画像のModelを取得
  // タイプに応じて取得先DBを選択
  public static function getMediaImgModel($img_id, $img_type){
    $media_img_model;
    switch ($img_type){
      case 1: // デフォルト画像
        $media_img_model = PublicImg::where('id', $img_id)->first();
        break;
      case 2: // ユーザがアップロードした画像
        $media_img_model = UserOwnImg::where('id', $img_id)->first();
        break;
    }
    return $media_img_model;
  }


}