<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\MediaContentsFieldUtil;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaContentsField;
use Storage;

class MediaContentsFieldController extends Controller
{
    // 1.index
    public static function index(){}
    // 2.create
    public static function create(Request $request){}

    // 3.store
    public static function store($media_id, $contents_field_data){
      MediaContentsFieldUtil::saveMediaContentsFieldData($media_id, $contents_field_data);
    }

    // 4.show
    public static function show($media_id){
        $contents_field_data = MediaContentsFieldUtil::getMediaContentsFieldData($media_id);
        return $contents_field_data;
    }

    // 5.edit
    public static function edit($id){}
    // 6.update
    public static function update($media_id, $request){
      MediaContentsFieldUtil::updateMediaContentsFieldData($media_id, $request);
    }
    // 7.destroy
    public static function destroy($media_id){
      MediaContentsField::where('media_id', $media_id)->first()->delete();
    }

}
