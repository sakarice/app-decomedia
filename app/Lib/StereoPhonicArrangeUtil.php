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
    \Log::info($media_audio_id);
    \Log::info($data);
    $model = new StereoPhonicArrange();
    $create_items = array();
    $create_items['media_audio_id'] = $media_audio_id;
    foreach(self::$COLUMN_AND_PROPERTY_LIST as $column => $property){
      $create_items[$column] = $data[$property];
    }
    $create_items['panningFlag'] = 1;

    \Log::info($create_items);
    $just_stored_data = $model->create($create_items);
  }

}