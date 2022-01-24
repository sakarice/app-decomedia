<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PublicImg;
use App\Models\ImgCategory;
use App\Models\PublicImgImgCategory;


class PublicImgUtil
{
  public static function updatePublicImgCategory(Request $request){
    $img_id = $request->img_id;
    $category = $request->category;
    $category_id = ImgCategory::where('category', $category)->first()->id;
    $model;
    if(PublicImgImgCategory::where('img_id',$img_id)->exists()){
      $model = PublicImgImgCategory::where('img_id',$img_id)->first();
    } else {
      $model = new PublicImgImgCategory;
    }
    $model->img_id = $img_id;
    $model->category_id = $category_id;
    $model->save();
  }
}