<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MediaAudioController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\MediaAudio;
use App\Models\StereoPhonicArrange;
use Storage;

class StereoPhonicArrangeUtil
{

  // テーブルのカラムとrequestのプロパティ名の対応を連想配列に定義
  private static $COLUMN_AND_PROPERTY_LIST = array(
    // 左(key)がテーブルのカラム名、右(value)がリクエストで送られてくるオブジェクトのプロパティ名
    'panningFlag' => 'panningFlag',
    'panningModel' => 'panningModel',
    'positionX' => 'positionX',
    'positionY' => 'positionY',
    'positionZ' => 'positionZ',

    // ★★ 他のキーは必要であれば追加の開発で実装
  );

  public static function getStereoPhonicArrangeData($media_audio_id){
    $data = StereoPhonicArrange::where('media_audio_id', $media_audio_id)->first()->toArray();
    return $data;
  }
  
  // 3.store // Media画像情報をDBに保存
  public static function saveStereoPhonicArrangeData($media_audio_id, $req_media_audio_data){
    $data = $req_media_audio_data;
    $model = new StereoPhonicArrange();
    $create_items = array();
    $create_items['media_audio_id'] = $media_audio_id;
    foreach(self::$COLUMN_AND_PROPERTY_LIST as $column => $property){
      $create_items[$column] = $data[$property];
    }
    $create_items['panningFlag'] = 1;
    $just_stored_data = $model->create($create_items);
  }

  public static function updateStereoPhonicArrangeData($media_audio_id, $req_media_audio_data){
    $model = StereoPhonicArrange::where('media_audio_id', $media_audio_id)->first();
    foreach(self::$COLUMN_AND_PROPERTY_LIST as $column => $property){
      $model->$column = $req_media_audio_data[$property];
    }
    $model->save();
  }

  // 空のAudioレコードを作成する。
  public static function addEmptyStereoPhonicArrangeData($media_audio_id){
    $model = new StereoPhonicArrange();
    $create_items = array();
    $create_items['media_audio_id'] = $media_audio_id;
    $create_items['panningFlag'] = 0;
    $create_items['panningModel'] = "equalpower";
    $create_items['positionX'] = 0;
    $create_items['positionY'] = 0;
    $create_items['positionZ'] = 0;

    $just_stored_data = $model->create($create_items);
    return $just_stored_data;
}


}