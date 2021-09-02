<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\RoomSettingController;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\DefaultImg;
use App\Models\RoomImg;
use Storage;

class ImgUtil
{

  // 画像ファイルの情報をDBに保存
  public static function saveImgData($fileDatas){
    $owner_user_id = $fileDatas['owner_user_id'];

    // 保存先DBを振り分け
    $targetModel;
    if($owner_user_id == NULL){
        $targetModel = new DefaultImg();
    } else {
        $targetModel = new UserOwnImg();
    }

    // モデルに値をセットして保存
    $targetModel->owner_user_id = $owner_user_id;
    $targetModel->name = pathinfo($fileDatas['name'], PATHINFO_FILENAME);
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