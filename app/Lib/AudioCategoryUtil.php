<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AudioCategory;


class AudioCategoryUtil
{
  public static function getAudioCategory(){
    $category = AudioCategory::pluck('category')->toArray();
    return ['category' => $category];
  }
}