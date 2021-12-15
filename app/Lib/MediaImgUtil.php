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

ini_set("log_errors", "on");
ini_set("error_log", "/logtest.txt");


class MediaImgUtil
{

  // テーブルのカラムとrequestのプロパティ名の対応を連想配列に定義
  private static $COLUMN_AND_PROPERTY_OF_MEDIA_IMG = array(
    // 左(key)がテーブルのカラム名、右(value)がリクエストで送られてくるオブジェクトのプロパティ名
    // 'media_id' => 'media_id',
    // 'id' => 'id',
    'img_id' => 'img_id',
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
      if(MediaImgUtil::checkIsStoreOKImg($object['img_id'])){
        $just_created_record = MediaImgUtil::saveMediaImgData($media_id, $object);
        $media_img_id = $just_created_record->id;
        MediaImgUtil::saveMediaImgSettingData($media_img_id, $object);
      }
    }
  }

  public static function saveMediaImgData($media_id, $data_object){
    $create_items = array();
    $create_items['media_id'] = $media_id;
    $create_items['owner_user_id'] = Auth::user()->id;
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG as $column_name => $property_name){
      $create_items[$column_name] = $data_object[$property_name];
    }
    $media_img = new MediaImg();
    $just_created_record = $media_img->create($create_items);
    return $just_created_record;
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

  public static function getMediaImgData($media_id){
    $send_media_imgs; // リターン対象のデータ
    $img_url = "";
    if(MediaImg::where('media_id', $media_id)->exists()){
      $media_img_db_datas = MediaImg::where('media_id', $media_id)->get();
      foreach($media_img_db_datas as $index => $media_img_db_data){
        // メディア画像テーブルのデータ取得
        $media_img = array();
        $img_url = MediaImgUtil::getMediaImgModel($media_img_db_data->img_id, $media_img_db_data->img_type)->img_url;
        $media_img['url'] = $img_url;        
        foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG as $column_name => $property_name){
          $media_img[$property_name] = $media_img_db_data[$column_name];        
        }
        // メディア画像設定テーブルのデータ取得
        $media_img_id = $media_img_db_data->id;
        $media_img_setting_db_data = MediaImgSetting::where('media_img_id', $media_img_id)->first();
        foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG_SETTING as $column_name => $property_name){
          $media_img[$property_name] = $media_img_setting_db_data[$column_name];        
        }
        $send_media_imgs[] = $media_img;
      }
    }
    return $send_media_imgs;
  }


  // 6.update
  public static function updateMediaImgsData($media_id, $objects){
    // 既存データは更新し、追加データは保存、リクエストに無いレコードは削除する。

    // 更新対象と新規追加対象の振り分け
    $update_req_datas = array();
    $create_req_datas = array();
    foreach($objects as $object){
      if(MediaImg::where('id', $object['id'])->exists()){
        $update_req_datas[] = $object;
      } else {
        $create_req_datas[] = $object;
      }
    }

    // 削除対象レコードの振り分け
    $media_img_ids_of_request = array();
    foreach($objects as $object){
      $media_img_ids_of_request[] = $object['id'];
    }

    $db_records_before_update = MediaImg::where('media_id', $media_id)->get();
    $delete_media_img_ids_in_db = array();
    foreach($db_records_before_update as $index => $db_record){
      if(!in_array($db_record->id, $media_img_ids_of_request)){
        $delete_media_img_ids_in_db[] = $db_record->id;
      }
    }

    error_log(print_r($update_req_datas),true);

    DB::beginTransaction();
    try{
      // 1. 更新対象リクエストデータを更新する。
      foreach($update_req_datas as $update_req_data){
        if(MediaImgUtil::checkIsStoreOKImg($update_req_data['img_id'])){
          $just_updated_media_img_record = MediaImgUtil::updateMediaImgData($media_id, $update_req_data);
          $media_img_id = $just_updated_media_img_record->id;
          MediaImgUtil::updateMediaImgSettingData($media_img_id, $update_req_data);
        } else {
          throw new \Exception('checkIsStoreOKImg Method NG');
        }
      }
      // 2. 新規作成対象のリクエストデータを登録する。
      if($create_req_datas){
        MediaImgUtil::saveMediaImgsData($media_id, $create_req_datas);
      }
      // 3. 削除対象レコードを削除する。
      if($delete_media_img_ids_in_db){
        MediaImg::whereIn('id', $delete_media_img_ids_in_db)->delete();
        MediaImgSetting::whereIn('media_img_id', $delete_media_img_ids_in_db)->delete();
      }

      DB::commit();
    } catch(\Exception $e) {
      DB::rollback();
    }

  }
  // メディア画像テーブルの更新
  public static function updateMediaImgData($media_id, $object){
    // $update_items = array();
    $target_record = MediaImg::where('media_id', $media_id)->where('id', $object['id'])->first();
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG as $column_name => $property_name){
      $target_record->$column_name = $object[$property_name];
    }
    $target_record->media_id = (int)$media_id;
    $target_record->owner_user_id = Auth::user()->id;
    $target_record->save();
    return $target_record;
  }
  // メディア画像設定テーブルの更新
  public static function updateMediaImgSettingData($media_img_id, $object){
    $target_record = MediaImgSetting::where('media_img_id', $media_img_id)->first();
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_IMG_SETTING as $column_name => $property_name){
      $target_record->$column_name = $object[$property_name];
    }
    $target_record->save();
  }

  // メディア画像として保存・更新してよいかチェック(他ユーザの画像使用などを防ぐ)
  public static function checkIsStoreOKImg($img_id){
    $isOwnImg = UserOwnImg::where('owner_user_id', Auth::user()->id)->where('id', $img_id)->exists();
    $isPublicImg = PublicImg::where('id', $img_id)->exists();
    if($isOwnImg || $isPublicImg){
      return true;
    } else {
      return false;
    }
  }

    // 【関数】MediaImgのレコード数をリクエストのImg数と同じにする
    public static function equalizeNumOfMediaImgDataWithRequest($media_id, $request_num){
      // 更新前のImg数とリクエストされたImg数を比較
      $imgNumBeforeUpdate = MediaImg::where('media_id', $media_id)->count();
      $imgNumDiff = $request_num - $imgNumBeforeUpdate;
      if($imgNumDiff > 0){
        // 足りない分だけ空のレコードを追加
        for($i=0; $i<$imgNumDiff; $i++){
          $dummy_media_img = MediaImgUtil::addDummyMediaImgData($media_id);
          MediaImgUtil::addDummyMediaImgSettingData($dummy_media_img->id);
        }
      } else if($imgNumDiff < 0){
        // 多い分だけレコードを削除
        for($i=0; $i<abs($imgNumDiff); $i++){
          MediaImg::where('media_id', $media_id)->orderBy('id', 'desc')->first()->delete();
        }
      }
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


  // 空のメディア画像レコードを作成する。
  public static function addDummyMediaImgData($media_id){
    $mediaImg = new MediaImg();
    $model = $mediaImg->create([
      'media_id' => $media_id,
      'img_type' => 1,
      'img_id' => 0,
      'width' => 100,
      'height' => 100,
      'opacity' => 1,
      'owner_user_id' => 0,
      'img_layer' => 1,
    ]);
    return $model;
  }
  // 空のメディア画像設定レコードを作成する。
  public static function addDummyMediaImgSettingData($media_img_id){
    $mediaImgSetting = new MediaImgSetting();
    $model = $mediaImgSetting->create([
      'media_img_id' => $media_img_id,
      'type' => 99,
      'user_selected_item_group_no' => null,
      'left' => 100,
      'top' => 100,
      'width' => 100,
      'height' => 100,
      'degree' => 0,
      'global_alpha' => 1,
      'layer' => 1,
    ]);
    return $model;
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