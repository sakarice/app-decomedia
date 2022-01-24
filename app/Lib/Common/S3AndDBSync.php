<?php

namespace App\Lib\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\ImgUtil;
use App\Lib\AudioUtil;
use App\Lib\StoreFileInS3;
use App\Lib\Common\StringProcessing;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\PublicImg;
use App\Models\UserOwnAudio;
use App\Models\UserOwnAudioThumbnail;
// use App\Models\UserOwnAudioAudioThumbnail;
use App\Models\PublicAudio;
use App\Models\PublicAudioThumbnail;
// use App\Models\PublicAudioAudioThumbnail;
use Storage;

class S3AndDBSync
{

  private static $S3PublicImgDir = "public/img/media";
  private static $S3PublicAudioDir = "public/audio/media";
  private static $S3PublicAudioThumbnailDir = "public/img/audio_thumbnail";

    public static function getS3FilePaths($directory) {
      $filePaths = Storage::disk('s3')->files($directory);
      return $filePaths;
    }

    public static function filterS3ImgFileDataNotExistInDB($s3_file_paths){
      // $datas = PublicImg::whereNotIn('img_path',$s3_file_paths)->pluck('img_path');
      $img_file_paths_db = PublicImg::pluck('img_path')->toArray();
      $filtered_data = array_diff($s3_file_paths, $img_file_paths_db);
      return $filtered_data;
    }

    public static function saveDiffImgDataInDB(){
      $s3_file_paths = S3AndDBSync::getS3FilePaths(self::$S3PublicImgDir);
      $paths_diff = S3AndDBSync::filterS3ImgFileDataNotExistInDB($s3_file_paths);
      $img_datas = [];
      foreach($paths_diff as $path_diff){
        $tmp_img_data = array(
          'owner_user_id' => NULL,
          'name' => preg_replace('{'.self::$S3PublicImgDir."/".'}', "", $path_diff),
          'path' => $path_diff,
          'url' => Storage::disk('s3')->url($path_diff),
        );
        $img_datas[] = $tmp_img_data;
      }
      foreach($img_datas as $img_data){
        ImgUtil::saveImgData($img_data);
      }
      return $img_datas;
    }


  
}