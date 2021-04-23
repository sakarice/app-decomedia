<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\UserOwnBmg;
use App\Models\Room;
use Storage;

class saveFile
{

  public static function saveFileInS3($user_id, $file){
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


  public static function saveUserOwnImgDataInDB($fileData){
    $userOwnImg = new UserOwnImg();

    $userOwnImg->owner_user_id = $fileData['owner_user_id'];
    $userOwnImg->name = $fileData['name'];
    $userOwnImg->img_path = $fileData['img_path'];
    $userOwnImg->img_url = $fileData['img_url'];

    $userOwnImg->save();
  }


          

}