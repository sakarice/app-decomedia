<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MediaFigureController;
use App\Models\User;
use App\Models\MediaFigure;

class MediaFigureUtil
{

  // テーブルのカラムとrequestのプロパティ名の対応を連想配列に定義
  private static $DB_REQ_RELATIONS = array(
    'media_id' => 'media_id',
    'type' => 'type',
    'user_selected_item_group_no' => 'groupNo',
    'left' => 'left',
    'top' => 'top',
    'width' => 'width',
    'height' => 'height',
    'degree' => 'degree',
    'global_alpha' => 'globalAlpha',
    'layer' => 'layer',
    'is_draw_fill' => 'isDrawFill',
    'fill_color' => 'fillColor',
    'is_draw_stroke' => 'isDrawStroke',
    'stroke_color' => 'strokeColor',
  );

  // 3.store // Media画像情報をDBに保存
  public static function saveMediaFigureData($media_id, $request){
    $req_media_figures = $request->figures;
    foreach($req_media_figures as $index => $req_media_figure){
      MediaFigureUtil::saveMediaFigureRecord($media_id, $req_media_figure);
    }
  }


  public static function saveMediaFigureRecord($media_id, $media_figure_data){
    $mediaFigure = new MediaFigure();
    $model = $mediaFigure->create([
      'media_id' => $media_id,
      'type' => $media_figure_data['type'],
      'user_selected_item_group_no' => $media_figure_data['groupNo'],
      'left' => $media_figure_data['left'],
      'top' => $media_figure_data['top'],
      'width' => $media_figure_data['width'],
      'height' => $media_figure_data['height'],
      'degree' => $media_figure_data['degree'],
      'global_alpha' => $media_figure_data['globalAlpha'],
      'layer' => $media_figure_data['layer'],
      'is_draw_fill' => $media_figure_data['isDrawFill'],
      'fill_color' => $media_figure_data['fillColor'],
      'is_draw_stroke' => $media_figure_data['isDrawStroke'],
      'stroke_color' => $media_figure_data['strokeColor'],
    ]);
    return $model;
  }

  public static function updateMediaFigureRecord($media_id, $req_datas){
    $target_records = MediaFigure::where('media_id', $media_id)->get();
    foreach($req_datas as $index => $req_data){
      foreach(self::$DB_REQ_RELATIONS as $column_name => $property_name){        
        $target_records[$index][$column_name] = $req_data[$property_name];
      }
      $target_records[$index]->save();
    }
  }



  // 4.show 
  // Media画像の情報を取得(Media作成、編集、閲覧時に使用)
  public static function getMediaFigureData($media_id){
    $media_figure_data = array();
    if(MediaFigure::where('media_id', $media_id)->exists()){
      $media_figures = MediaFigure::where('media_id', $media_id)->get();

      // Media図形の情報を連想配列として一つずつ取得し、配列に追加していく
      foreach($media_figures as $media_figure){
        $tmp_media_figure_data = [
          'media_id' => $media_figure->media_id,
          'type' => $media_figure->type,
          'groupNo' => $media_figure->group_no,
          'left' => $media_figure->left,
          'top' => $media_figure->top,
          'width' => $media_figure->width,
          'height' => $media_figure->height,
          'degree' => $media_figure->degree,
          'globalAlpha' => $media_figure->global_alpha,
          'layer' => $media_figure->layer,
          'isDrawFill' => $media_figure->is_draw_fill,
          'fillColor' => $media_figure->fill_color,
          'isDrawStroke' => $media_figure->is_draw_stroke,
          'strokeColor' => $media_figure->stroke_color,
        ];
        $media_figure_data[] = $tmp_media_figure_data;      
      }
    }
    return $media_figure_data;
  }



  // 【関数】MediaFigureのレコード数をリクエストのFigure数と同じにする
  public static function equalizeNumOfMediaFigureDataWithRequest($media_id, $request_num){
    // 更新前のFigure数とリクエストされたFigure数を比較
    $figureNumBeforeUpdate = MediaFigure::where('media_id', $media_id)->count();
    $figureNumDiff = $request_num - $figureNumBeforeUpdate;
    if($figureNumDiff > 0){
      // 足りない分だけ空のレコードを追加
      for($i=0; $i<$figureNumDiff; $i++){
        MediaFigureUtil::addEmptyMediaFigureData($media_id);
      }
    } else if($figureNumDiff < 0){
      // 多い分だけレコードを削除
      for($i=0; $i<abs($figureNumDiff); $i++){
        MediaFigure::where('media_id', $media_id)->orderBy('id', 'desc')->first()->delete();
      }
    }
  }

  // 空のFigureレコードを作成する。
  public static function addEmptyMediaFigureData($media_id){
    $mediaFigure = new MediaFigure();
    $model = $mediaFigure->create([
      'media_id' => $media_id,
      'type' => 1,
      'user_selected_item_group_no' => null,
      'left' => 100,
      'top' => 100,
      'width' => 100,
      'height' => 100,
      'degree' => 0,
      'global_alpha' => 1,
      'layer' => 1,
      'is_draw_fill' => false,
      'fill_color' => "#000000",
      'is_draw_stroke' => true,
      'stroke_color' => "#000000",
    ]);
    return $model;
  }


  // 6.update
  public static function updateMediaFigureData($media_id, $request){
    \Log::info('start updateMediaFigureData');
    $req_media_figures;
    $req_figure_num;
    if(isset($request->figures[0])){ // リクエストにfigureがセットされている場合
      // リクエストのメディア図形データを取得
      $req_media_figures = $request->figures;
      $req_figure_num = count($req_media_figures);
      // DBのレコード数をリクエストのfigure数と同じにする
      MediaFigureUtil::equalizeNumOfMediaFigureDataWithRequest($media_id, $req_figure_num);
      // DBのメディア図形データを取得
      // $db_media_figures = MediaFigure::where('media_id', $media_id)->get();
      MediaFigureUtil::updateMediaFigureRecord($media_id, $req_media_figures);
      \Log::info('finish updateMediaFigureData');
    } else { // セットされていなかった場合
      $req_figure_num = 0;
      // DBのMediaAudioのレコードを削除。更新処理は行わない。
      MediaFigureUtil::equalizeNumOfMediaFigureDataWithRequest($media_id, $req_figure_num);
    }

  }
 


}
