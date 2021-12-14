<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MediaMovieController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaMovie;
use Storage;

class MediaMovieUtil
{

  // テーブルのカラムとrequestのプロパティ名の対応を連想配列に定義
  private static $COLUMN_AND_PROPERTY_OF_MEDIA_MOVIE = array(
    // 左(key)がテーブルのカラム名、右(value)がリクエストで送られてくるオブジェクトのプロパティ名
    // 'media_id' => 'media_id',
    // 'id' => 'id',
    // 'media_id' => 'mediaId',
    // 'user_id' => 'userId',
    'video_id' => 'videoId',
    'left' => 'left',
    'top' => 'top',
    'width' => 'width',
    'height' => 'height',
    'opacity' => 'opacity',
    'movie_layer' => 'layer',
    'isLoop' => 'isLoop',
  );


  // 3.store // Media動画情報をDBに保存
  public static function saveMediaMovieData($media_id, $request){
    // カラム名と保存用データのペアの連想配列を作成
    $create_items = array();
    $create_items['user_id'] = Auth::user()->id;
    $create_items['media_id'] = $media_id;
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_MOVIE as $column_name => $property_name){
      $create_items[$column_name] = $request->movie[$property_name];
    }

    // DB保存
    $media_movie = new MediaMovie();
    $just_created_record = $media_movie->create($create_items);
    return $just_created_record;
  }

  // 4.show 
  // Media動画の情報を取得(Media作成、編集、閲覧時に使用)
  public static function getMediaMovieData($media_id){
    if(MediaMovie::where('media_id', $media_id)->exists()){
      $db_record = MediaMovie::where('media_id', $media_id)->first();            
      $send_data = array();
      foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_MOVIE as $column_name => $property_name){
        $send_data[$property_name] = $db_record[$column_name];
      }
      $send_data['id'] = $db_record['id'];
    } else { // DBに動画設定が保存されていなければ、デフォルト値を設定
      $send_data = MediaMovieUtil::makeDefaultMovieData(); 
    }
    return $send_data;
  }

  // 6.update
  public static function updateMediaMovieData($media_id, $request){
    $mediaMovie;
    if(MediaMovie::where('media_id', $media_id)->exists()){
      $update_record = MediaMovie::where('media_id', $media_id)->first();
    } else {
      $update_record = new MediaMovie;
      $update_record->user_id = Auth::user()->id;
      $update_record->media_id = $media_id;
    }
    foreach(self::$COLUMN_AND_PROPERTY_OF_MEDIA_MOVIE as $column_name => $property_name){
      $update_record->$column_name = $request->movie[$property_name];
    }
    $update_record->save();
  }


  public static function makeDefaultMovieData(){
    $media_movie_data = [
      'id' => -1,
      'videoId' => "",
      'left' => 0,
      'top' => 0,
      'width' => 400,
      'height' => 300,
      'opacity' => 1,
      'isLoop' => false,
      'layer' => 1,
    ];
    return $media_movie_data;
  }




}