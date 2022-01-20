<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PublicAudio;
use App\Models\AudioCategory;
use App\Models\PublicAudioAudioCategory;


class PublicAudioUtil
{
  public static function updatePublicAudioCategory(Request $request){
    $audio_id = $request->audio_id;
    $category = $request->category;
    $category_id = AudioCategory::where('category', $category)->first()->id;
    $model;
    if(PublicAudioAudioCategory::where('audio_id',$audio_id)->exists()){
      $model = PublicAudioAudioCategory::where('audio_id',$audio_id)->first();
    } else {
      $model = new PublicAudioAudioCategory;
    }
    $model->audio_id = $audio_id;
    $model->category_id = $category_id;
    $model->save();
  }
}