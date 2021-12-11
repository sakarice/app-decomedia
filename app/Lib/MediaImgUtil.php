<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RooImgController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\MediaImg;
use App\Models\MediaImgSetting;
use App\Models\PublicImg;
use App\Models\UserOwnImg;
use Storage;

class MediaImgUtil
{

  // テーブルのカラムとrequestのプロパティ名の対応を連想配列に定義
  private static $COLUMN_AND_PROPERTY_OF_MEDIA_IMG = array(
    // 左(key)がテーブルのカラム名、右(value)がリクエストで送られてくるオブジェクトのプロパティ名
    // 'media_id' => 'media_id',
    'img_id' => 'id',
    'img_type' => 'img_type',
    'width' => 'width',
    'height' => 'height',
    'opacity' => 'opacity',
    // "owner_user_id" => "",
    'img_layer' => 'layer',
  );

  private static $COLUMN_AND_PROPERTY_OF_MEDIA_IMG_SETTING = array(
    // "media_img_id" => "",
    "type" => "type",
    "user_selected_item_group_no" => "groupNo",
    "left" => "left",
    "top" => "top",
    "width" => "width",
    "height" => "height",
    "degree" => "degree",
    "global_alpha" => "opacity",
    "layer" => "layer",
  );


  // 3.store // Media画像情報をDBに保存
  // 複数のメディア画像保存用メソッド
  public static function saveMediaImgsData($media_id, $objects){
    foreach($objects as $index => $object){
      MediaImgUtil::saveMediaImgData($media_id, $object);
      $media_img_id = MediaImg::latest()->first()->id;
      \Log::info($media_img_id);
      MediaImgUtil::saveMediaImgSettingData($media_img_id, $object);
    }
  }

  public static function saveMediaImgData($media_id, $object){
    $mediaImg = new MediaImg();
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG as $column_name => $property_name){
      $mediaImg[$column_name] = $object[$property_name];
    }
    $mediaImg['media_id'] = $media_id;
    $mediaImg['owner_user_id'] = Auth::user()->id;
    $mediaImg->save();
  }
  public static function saveMediaImgSettingData($media_img_id, $object){
    $mediaImgSetting = new MediaImgSetting();
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG_SETTING as $column_name => $property_name){
      $mediaImgSetting[$column_name] = $object[$property_name];
    }
    $mediaImgSetting['media_img_id'] = $media_img_id;
    $mediaImgSetting->save();
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
    $send_data; // リターン対象のデータ
    $img_url = "";
    $db_data = MediaImg::where('media_id', $media_id)->first();
    if($db_data->img_id == 0){
      $send_data = MediaImgUtil::getEmptyMediaImgData();
    } else {
      $img_url = MediaImgUtil::getMediaImgModel($db_data->img_id, $db_data->img_type)->img_url;
      $send_data = array();
      $send_data['url'] = $img_url;
      $send_data['type'] = 97;
      foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG as $column_name => $property_name){
        $send_data[$property_name] = $db_data[$column_name];        
      }
    };
    return $send_data;
}


  // 6.update
  public static function updateMediaImgData($media_id, $request){
    $target_record = MediaImg::where('media_id', $media_id)->first();
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG as $column_name => $property_name){
      $target_record[$column_name] = $request->img[$property_name];
    }
    $target_record->save();
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