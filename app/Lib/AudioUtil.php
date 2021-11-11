<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Bgm\AudioController;
use App\Models\User;
use App\Models\UserOwnAudio;
use App\Models\UserOwnAudioThumbnail;
use App\Models\UserOwnAudioAudioThumbnail;
use App\Models\PublicAudio;
use App\Models\PublicAudioThumbnail;
use App\Models\PublicAudioAudioThumbnail;
use Storage;

class AudioUtil
{

  // オーディオファイルの情報をDBに保存
  // (サムネイルの情報も保存するので、カラムが画像の保存より多い)
  public static function saveAudioData($fileDatas){
    $owner_user_id = $fileDatas['owner_user_id'];

    // 保存先DBを振り分け
    $targetModel;
    if($owner_user_id == NULL){
        $targetModel = new PublicAudio();
    } else {
        $targetModel = new UserOwnAudio();
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


  public static function saveAudioThumbnailData($fileDatas){
    $owner_user_id = $fileDatas['owner_user_id'];

    // 保存先DBを振り分け
    $targetModel;
    if($owner_user_id == NULL){
        $targetModel = new PublicAudioThumbnail();
    } else {
        $targetModel = new UserOwnAudioThumbnail();
    }

    $targetModel->owner_user_id = $owner_user_id;
    $targetModel->name = $fileDatas['name'];
    $targetModel->img_path = $fileDatas['path'];
    $targetModel->img_url = $fileDatas['url'];
    $targetModel->save();

    // 保存したレコードのidを取得
    $id = $targetModel->where('owner_user_id', $owner_user_id)
                      ->where('img_url', $fileDatas['url'])
                      ->first()
                      ->id;
    return $id;

  }

}