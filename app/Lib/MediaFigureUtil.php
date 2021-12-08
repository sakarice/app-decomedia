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

  // 3.store // Media画像情報をDBに保存
  public static function saveMediaFigureData($media_id, $request){
    $req_media_figures = $request->figures;
    foreach($req_media_figures as $index => $req_media_figure){
      $mediaFigure = new MediaFigure();
      $mediaFigure->media_id = $media_id;
      $mediaFigure->type = $req_media_figure['type'];
      $mediaFigure->user_selected_item_group_no = $req_media_figure['groupNo'];
      $mediaFigure->left = $req_media_figure['left'];
      $mediaFigure->top = $req_media_figure['top'];
      $mediaFigure->width = $req_media_figure['width'];
      $mediaFigure->height = $req_media_figure['height'];
      $mediaFigure->degree = $req_media_figure['degree'];
      $mediaFigure->global_alpha = $req_media_figure['globalAlpha'];
      $mediaFigure->layer = $req_media_figure['layer'];
      $mediaFigure->is_draw_fill = $req_media_figure['isDrawFill'];
      $mediaFigure->fill_color = $req_media_figure['fillColor'];
      $mediaFigure->is_draw_stroke = $req_media_figure['isDrawStroke'];
      $mediaFigure->stroke_color = $req_media_figure['strokeColor'];
      $mediaFigure->save();
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


}
