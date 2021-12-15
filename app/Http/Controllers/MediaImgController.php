<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Lib\ImgUtil;
use App\Lib\MediaImgUtil;
use App\Models\User;
use App\Models\MediaImg;
use Storage;

class MediaImgController extends Controller
{
    // 1.index
    public function index(){}
    // 2.create
    public function create(Request $request){}
    // 3.store
    public static function store($media_id, $received_datas){
        // MediaImgUtil::saveMediaImgData($media_id, $request->img);
        MediaImgUtil::saveMediaImgsData($media_id, $received_datas);
    }

    // 4.show   // Media画像情報の連想配列を返す
    public static function show($media_id){
        $media_img_data = MediaImgUtil::getMediaImgData($media_id);
        return $media_img_data;
    }
    
    // 5.edit
    public function edit($media_id){}
    // 6.update
    public static function update($media_id, $received_datas){
        // MediaImgUtil::updateMediaImgData($media_id, $request->imgs);
        MediaImgUtil::updateMediaImgsData($media_id, $received_datas);
    }
    // 7.destroy
    public static function destroy($media_id){
        MediaImg::where('media_id', $media_id)->first()->delete();
    }
    
}
