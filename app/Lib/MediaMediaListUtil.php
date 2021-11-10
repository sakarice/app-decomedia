<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Media;
use App\Models\Medialist;
use App\Models\MediaMedialist;
use Storage;

class MediaMediaListUtil
{

  // media - mediaリスト情報をDBから削除
  public static function deleteMediaMediaListDataFromDB($media_list_id){
    MediaMedialist::where('medialist_id', $media_list_id)
    ->first()
    ->delete();  
  }

}