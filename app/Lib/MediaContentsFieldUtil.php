<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaContentsField;
use App\Models\MediaSetting;
use Storage;

class MediaContentsFieldUtil
{

  private static $NAME_PAIRS_IN_COLUMN_AND_PROPERTY = array(
    // 左(key)がテーブルのカラム名、右(value)がリクエストで送られてくるオブジェクトのプロパティ名
    'media_id' => 'media_id',
    'width' => 'width',
    'height' => 'height',
    'color' => 'color',
    'img_url' => 'img_url',
    'img_size_type' => 'img_size_type',
  );


  // 3.store
  public static function saveMediaContentsFieldData($media_id, $media_contents_field_data){
    $new_model = new MediaContentsField();
    $create_items = array();
    $media_contents_field_data['media_id'] = $media_id;
    foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column_name => $property_name){
      $create_items[$column_name] = $media_contents_field_data[$property_name];
    }
    $stored_model = $new_model->create($create_items);
    return $stored_model;
  }

  // 4. show
  public static function getMediaContentsFieldData($media_id){
    $db_data = MediaContentsField::where('media_id', $media_id)->first();
    $send_data = array();
    foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column_name => $property_name){
      $send_data[$property_name] = $db_data->$column_name;
    }
    return $send_data;
  }

  // 6.update
  public static function updateMediaContentsFieldData($media_id, $media_contents_field_data){
    $taget_record = MediaContentsField::where('media_id', $media_id)->first();
    foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column_name => $property_name){
      $taget_record->$column_name = $media_contents_field_data[$property_name];
    }
    $taget_record->save();
  }
}