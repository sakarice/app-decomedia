<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\MediaTextUtil;
use App\Lib\storeFileInS3;
use App\Models\User;
use App\Models\MediaText;
use Storage;


class MediaTextController extends Controller
{
        // 1.index
        public static function index(){}
        // 2.create
        public static function create(Request $request){}
        // 3.store
        public static function store($media_id, $request){
            MediaTextUtil::saveMediaTextData($media_id, $request);
        }
        // 6.update
        public static function update($media_id, $request){
            MediaTextUtil::updateMediaTextData($media_id, $request);
        }
        // 7.destroy
        public static function destroy($media_id, $request){
            MediaText::where('media_id', $media_id)->delete();
        }

}
