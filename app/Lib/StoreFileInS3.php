<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\DefaultImg;
use App\Models\UserOwnBgm;
use App\Models\DefaultBgm;
use App\Models\Room;
use Storage;

class StoreFileInS3
{

  public static function userOwnFile($user_id, $file){
    // ファイルタイプに応じたS3の保存先ディレクトリを設定
    $directry;
    switch(true){
      case preg_match('/^image/', $file->getMimeType()):
        $directry = "user/".$user_id."/files/img";
        break;
      case preg_match('/^audio/', $file->getMimeType()): 
        $directry = "user/".$user_id."/files/audio";
        break;
    }
    // S3へファイル保存 & DBへ登録する保存先パスを取得
    $filePath = Storage::disk('s3')->putFile($directry, $file, 'public');
    return $filePath;
  }



  public static function PublicFile($request, $fileType){
    // ファイルの内容に応じた保存先ディレクトリを指定
    switch($fileType){
      case 'img':
        $directry = "public/img/media";
        break;
      case 'audio':
        $directry = "public/audio/media";
        break;
      case 'audio-thumbnail':
        $directry = "public/img/audio_thumbnail";
        break;
    }
    
    // S3へファイル保存 & 保存先パスを返す
    $file = $request->file($fileType);
    $filePath = Storage::disk('s3')->putFile($directry, $file, 'public');
    return $filePath;

  }

}