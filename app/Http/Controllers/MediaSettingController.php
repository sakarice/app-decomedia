<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\MediaSettingUtil;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaSetting;
use Storage;

class MediaSettingController extends Controller
{
    // 1.index
    public static function index(){}
    // 2.create
    public static function create(Request $request){}

    // 3.store
    public static function store($media_id, $request){
      MediaSettingUtil::saveMediaSettingData($media_id, $request);
    }

    // 4.show
    public static function show($media_id){
        $media_setting_data = MediaSettingUtil::getMediaSettingData($media_id);
        return $media_setting_data;
    }

    // 5.edit
    public static function edit($id){}
    // 6.update
    public static function update($media_id, $request){
      MediaSettingUtil::updateMediaSettingData($media_id, $request);
    }
    // 7.destroy
    public static function destroy($media_id){
      MediaSetting::where('media_id', $media_id)->first()->delete();
    }

}
