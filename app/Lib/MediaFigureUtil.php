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

}
