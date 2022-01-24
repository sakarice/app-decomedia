<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ImgCategory;


class ImgCategoryUtil
{
  public static function getImgCategory(){
    $category = ImgCategory::pluck('category')->toArray();
    return ['category' => $category];
  }
}