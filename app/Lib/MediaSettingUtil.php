<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MediaSettingController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaSetting;
use Storage;

class MediaSettingUtil
{

  // 3.store // Media設定情報をDBに保存
  public static function saveMediaSettingData($media_id, $request){
    // $user_id = Auth::user()->id;
    \Log::info('start saveMediaSettingData');
    \Log::info($request->setting);
    $mediaSetting = new MediaSetting();
    $mediaSetting->media_id = $media_id;
    $mediaSetting->open_state = $request->setting['isPublic'];
    if(isset($request->setting['name'])){
      $mediaSetting->name = $request->setting['name'];
    }else {
      $mediaSetting->name = 'media';
    }
    $mediaSetting->description = $request->setting['description'];
    $mediaSetting->finish_time = $request->setting['finish_time'];
    $mediaSetting->is_show_img = $request->setting['isShowImg'];
    $mediaSetting->is_show_movie = $request->setting['isShowMovie'];
    $mediaSetting->max_audio_num = $request->setting['maxAudioNum'];
    // $mediaSetting->background_type = $request->setting['mediaBackgroundType'];
    $mediaSetting->background_color = $request->setting['mediaBackgroundColor'];
    \Log::info('end saveMediaSettingData');
    $mediaSetting->save();
  }

  // 4. show // Media設定情報を取得
  public static function getMediaSettingData($media_id){
    $media_setting = MediaSetting::where('media_id', $media_id)->first();
    $media_setting_data = [
        'id' => $media_id,
        'name' => $media_setting->name,
        'description' => $media_setting->description,
        'finish_time' => $media_setting->finish_time,
        'is_show_img' => $media_setting->is_show_img,
        'is_show_movie' => $media_setting->is_show_movie,
        'max_audio_num' => $media_setting->max_audio_num,
        'background_type' => $media_setting->background_type,
        'background_color' => $media_setting->background_color,
        'chat_valid_flag' => $media_setting->chat_valid_flag,
        'isPublic' => $media_setting->open_state,
        'enter_limit' => $media_setting->enter_limit,
    ];
    return $media_setting_data;
  }

  // 6.update
  public static function updateMediaSettingData($media_id, $request){
    $mediaSetting = MediaSetting::where('media_id', $media_id)->first();
    $mediaSetting->open_state = $request->setting['isPublic'];
    if(isset($request->setting['name'])){
      $mediaSetting->name = $request->setting['name'];
    }else {
      $mediaSetting->name = 'media';
    }
    $mediaSetting->description = $request->setting['description'];
    $mediaSetting->finish_time = $request->setting['finish_time'];
    $mediaSetting->is_show_img = $request->setting['isShowImg'];
    $mediaSetting->is_show_movie = $request->setting['isShowMovie'];
    $mediaSetting->max_audio_num = $request->setting['maxAudioNum'];
    // $mediaSetting->background_type = $request->setting['mediaBackgroundType'];
    $mediaSetting->background_color = $request->setting['mediaBackgroundColor'];
    $mediaSetting->save();
  }


}