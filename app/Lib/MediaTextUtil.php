<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MediaTextController;
use App\Models\User;
use App\Models\MediaText;

class MediaTextUtil
{

  // テーブルのカラムとrequestのプロパティ名の対応を連想配列に定義
  private static $NAME_PAIRS_IN_COLUMN_AND_PROPERTY = array(
    // 左(key)がテーブルのカラム名、右(value)がリクエストで送られてくるオブジェクトのプロパティ名
    'media_id' => 'media_id',
    'type' => 'type',
    'user_selected_item_group_no' => 'groupNo',
    'text' => 'text',
    'left' => 'left',
    'top' => 'top',
    'width' => 'width',
    'height' => 'height',
    'scale_x' => 'scale_x',
    'scale_y' => 'scale_y',
    'color' => 'color',
    'font_size' => 'font_size',
    'font_category' => 'font_category',
    'font_family' => 'font_family',
    'degree' => 'degree',
    'opacity' => 'opacity',
    'layer' => 'layer',
  );

  // 3.store // Mediaテキスト情報をDBに保存
  public static function saveMediaTextData($media_id, $request){
    $req_media_texts = $request->texts;
    foreach($req_media_texts as $index => $req_media_text){
      MediaTextUtil::saveMediaTextRecord($media_id, $req_media_text);
    }
  }

  public static function saveMediaTextRecord($media_id, $media_text_data){
    $mediaText = new MediaText();
    $create_items = array();
    $media_text_data['media_id'] = $media_id; // メディアIDだけ初期化しておく
    foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column_name => $property_name){
      $create_items[$column_name] = $media_text_data[$property_name];
    }
    $model = $mediaText->create($create_items);
    return $model;
  }

  // 4.show 
  // Mediaテキストの情報を取得(Media作成、編集、閲覧時に使用)
  public static function getMediaTextData($media_id){
    $media_texts = array();
    if(MediaText::where('media_id', $media_id)->exists()){
      $db_datas = MediaText::where('media_id', $media_id)->get();
      // Mediaテキストの情報を連想配列として一つずつ取得し、配列に追加していく
      $text_object = array();
      foreach($db_datas as $db_data){
        foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column_name => $property_name){
          $text_object[$property_name] = $db_data[$column_name];
        }
        $media_texts[] = $text_object;
      }
    }
    return $media_texts;
  }

  // 【関数】MediaTextのレコード数をリクエストのText数と同じにする
  public static function equalizeNumOfMediaTextDataWithRequest($media_id, $request_num){
    // 更新前のText数とリクエストされたText数を比較
    $textNumBeforeUpdate = MediaText::where('media_id', $media_id)->count();
    $textNumDiff = $request_num - $textNumBeforeUpdate;
    if($textNumDiff > 0){
      // 足りない分だけ空のレコードを追加
      for($i=0; $i<$textNumDiff; $i++){
        MediaTextUtil::addEmptyMediaTextData($media_id);
      }
    } else if($textNumDiff < 0){
      // 多い分だけレコードを削除
      for($i=0; $i<abs($textNumDiff); $i++){
        MediaText::where('media_id', $media_id)->orderBy('id', 'desc')->first()->delete();
      }
    }
  }

  // 空のTextレコードを作成する。
  public static function addEmptyMediaTextData($media_id){
    $mediaText = new MediaText();
    $model = $mediaText->create([
      'media_id' => $media_id,
      'type' => 1,
      'user_selected_item_group_no' => null,
      'text' => "textテキスト",
      'left' => 100,
      'top' => 100,
      'width' => 100,
      'height' => 100,
      'scale_x' => 1,
      'scale_y' => 1,
      'color' => '#000000',
      'font_size' => 18,
      'font_category' => 'normal',
      'font_family' => 'monospace',
      'degree' => 0,
      'global_alpha' => 1,
      'layer' => 1,
    ]);
    return $model;
  }


  // 6.update
  public static function updateMediaTextData($media_id, $request){
    $req_media_texts;
    $req_text_num;
    if(isset($request->texts[0])){ // リクエストにtextがセットされている場合
      // リクエストのメディアテキストデータを取得
      $req_media_texts = $request->texts;
      $req_text_num = count($req_media_texts);
      // DBのレコード数をリクエストのtext数と同じにする
      MediaTextUtil::equalizeNumOfMediaTextDataWithRequest($media_id, $req_text_num);
      // DBのメディアテキストデータを取得
      // $db_media_texts = MediaText::where('media_id', $media_id)->get();
      MediaTextUtil::updateMediaTextRecord($media_id, $req_media_texts);
    } else { // セットされていなかった場合
      $req_text_num = 0;
      // DBのMediaAudioのレコードを削除。更新処理は行わない。
      MediaTextUtil::equalizeNumOfMediaTextDataWithRequest($media_id, $req_text_num);
    }
  }

  public static function updateMediaTextRecord($media_id, $req_datas){
    $target_records = MediaText::where('media_id', $media_id)->get();
    foreach($req_datas as $index => $req_data){
      foreach(self::$NAME_PAIRS_IN_COLUMN_AND_PROPERTY as $column_name => $property_name){
        $target_records[$index][$column_name] = $req_data[$property_name];
      }
      $target_records[$index]['media_id'] = $media_id;
      $target_records[$index]->save();
    }
  }

 


}
