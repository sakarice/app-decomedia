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

class SaveFile
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


  public static function saveDefaultFileInS3($req, $tag){
    $directry = "default/room/";
    // ファイルの内容に応じた保存先ディレクトリを指定
    switch($tag){
      case 'img':
        $directry .= "img";
        break;
      case 'audio':
        $directry .= "audio/audio_file";
        break;
        case 'audio-thumbnail':
        $directry .= "audio/thumbnail";
        break;
    }
    
    // S3へファイル保存 & 保存先パスを返す
    $file = $req->file($tag);
    $filePath = Storage::disk('s3')->putFile($directry, $file, 'public');
    return $filePath;

  }


  // 画像ファイルの情報をDBに保存
  public static function saveImgDataInDB($fileDatas){
    $owner_user_id = $fileDatas['owner_user_id'];

    // 保存先DBを振り分け
    $targetModel;
    if($owner_user_id == NULL){
        $targetModel = new DefaultImg();
    } else {
        $targetModel = new UserOwnImg();
    }

    $targetModel->owner_user_id = $owner_user_id;
    $targetModel->name = $fileDatas['name'];
    $targetModel->img_path = $fileDatas['img_path'];
    $targetModel->img_url = $fileDatas['img_url'];

    $targetModel->save();

    // 保存したレコードのidを取得
    $id = $targetModel->where('owner_user_id', $owner_user_id)
                      ->where('img_url', $fileDatas['img_url'])
                      ->first()
                      ->id;

    return $id;
  }

  // オーディオファイルの情報をDBに保存
  // (サムネイルの情報も保存するので、カラムが画像の保存より多い)
  public static function saveAudioDataInDB($fileDatas){
    $owner_user_id = $fileDatas['owner_user_id'];

    // 保存先DBを振り分け
    $targetModel;
    if($owner_user_id == NULL){
        $targetModel = new DefaultBgm();
    } else {
        $targetModel = new UserOwnBgm();
    }

    $targetModel->owner_user_id = $owner_user_id;
    $targetModel->name = $fileDatas['name'];
    $targetModel->audio_path = $fileDatas['path'];
    $targetModel->audio_url = $fileDatas['url'];
    $targetModel->thumbnail_path = $fileDatas['thumbnail_path'];
    $targetModel->thumbnail_url = $fileDatas['thumbnail_url'];

    $targetModel->save();

    // 保存したレコードのidを取得
    $id = $targetModel->where('owner_user_id', $owner_user_id)
                      ->where('audio_url', $fileDatas['url'])
                      ->first()
                      ->id;

    return $id;
  }

          

}