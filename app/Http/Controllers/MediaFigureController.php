<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\MediaFigureUtil;
use App\Lib\storeFileInS3;
use App\Models\User;
use App\Models\MediaFigure;
use Storage;


class MediaFigureController extends Controller
{
        // 1.index
        public static function index(){}
        // 2.create
        public static function create(Request $request){}
        // 3.store
        public static function store($media_id, $request){
            MediaFigureUtil::saveMediaFigureData($media_id, $request);
        }

        // // 4.show
        // public static function show($media_id){
        //     $media_audio_data = MediaFigureUtil::getMediaFigureData($media_id);    
        //     return $media_audio_data;
        // }

        // // 5.edit
        // public static function edit($media_id){}
        // 6.update
        public static function update($media_id, $request){
            MediaFigureUtil::updateMediaFigureData($media_id, $request);
        }
        // // 7.destroy
        // public static function destroy($media_id){
        //     MediaFigure::where('media_id', $media_id)->delete();
        // }
    
}
