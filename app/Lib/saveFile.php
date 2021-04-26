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


  public static function saveDefaultImgFileInS3($file){
    $directry = "img/room";
    // S3へファイル保存 & DBへ登録する保存先パスを取得
    $filePath = Storage::disk('s3')->putFile($directry, $file, 'public');
    return $filePath;

  }


  public static function saveImgDataInDB($fileData){
    $owner_user_id = $fileData['owner_user_id'];
    $targetModel;

    // user_idに応じて保存先DBを振り分け
    if($owner_user_id == NULL){
      $targetModel = new DefaultImg();
    } else {
      $targetModel = new UserOwnImg();
    }

    $targetModel->owner_user_id = $owner_user_id;
    $targetModel->name = $fileData['name'];
    $targetModel->img_path = $fileData['img_path'];
    $targetModel->img_url = $fileData['img_url'];

    $targetModel->save();
  }


          

}