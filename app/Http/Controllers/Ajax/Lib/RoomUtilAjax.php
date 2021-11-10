<?php

namespace App\Http\Controllers\Ajax\Lib;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;

use Storage;

class MediaUtilAjax
{
    // 入ったmediaが自分の作成したmediaか判別する(※ログインしている前提)
    public static function judgeIsMyMedia($media_id){
        $enter_user_id = Auth::user()->id;
        $media_owner_user_id = Media::find($media_id)->user_id;
        $isMyMedia;

        if($enter_user_id == $media_owner_user_id){
            $isMyMedia = true;
        } else {
            $isMyMedia = false;
        }

        return ['isMyMedia' => $isMyMedia];
    }

}
