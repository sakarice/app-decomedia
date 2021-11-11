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
use App\Models\PublicAudio;
use App\Models\PublicAudioThumbnail;
use App\Models\PublicAudioAudioThumbnail;

use Storage;

class Functions
{

    public function view() {
      $fileNames = [
        'imgFileUrl' => "",
        'audioFileUrl' => "",
        'audioThumbnailFileUrl' => ""
      ];

      return view('upload.publicFile', $fileNames);
    }
  
    
    public function uploadFile(Request $request){
      $imgFileName = "";
      $audioFileName = "";
      $audioThumbnailFileName = "";
      
      // ファイルのバリデーションチェック
      function checkFile($req, $filetag){
        $isFileExist = $req->hasFile($filetag);
        if($isFileExist){
          $isUploaded = $req->file($filetag)->isValid();
          if($isUploaded){
            return true;
          } else {
            return false;
          }
        } else {
          return false;
        }
      };

      // S3へのファイル保存とDBに登録するデータ作成
      function storeFileAndcreateDataForDb($request, $type){

        // ファイル名から拡張子を除外する
        $originalFilename = $request->file($type)->getClientOriginalName();
        $extExceptedFilename = StringProcessing::getFilenameExceptExt($originalFilename);

        $fileName = $extExceptedFilename;
        $filePath = StoreFileInS3::PublicMediaFile($request, $type);
        $fileUrl = Storage::disk('s3')->url($filePath);

        
        $fileDatas = array (
          'owner_user_id' => NULL,
          'name' => $fileName,
          'path' => $filePath,
          'url' => $fileUrl
        );
        return $fileDatas;
      }

      // 下記処理で参照する変数を宣言しておく
      $isExistsImg = false;
      $isExistsAudio = false;
      $isExistsAudioThumbnail = false;
      $audio_id;

      // 画像ファイルの保存とDB登録
      if($isExistsImg = checkFile($request, 'img')){
        $imgFileDatas = storeFileAndcreateDataForDb($request ,'img');
        ImgUtil::saveImgData($imgFileDatas);
      }
      // オーディオファイルの保存とDB登録
      if($isExistsAudio = checkFile($request, 'audio')){
        $audioFileDatas = storeFileAndcreateDataForDb($request ,'audio');
        
        // ※★↓サムネは中間テーブルに移行したため、動作検証後に消すこと
        $audioFileDatas += array('thumbnail_path' => "");
        $audioFileDatas += array('thumbnail_url' => "");

        $audio_id = AudioUtil::saveAudioData($audioFileDatas);

        // オーディオとサムネイルの中間テーブルにもデータを保存
        $public_audio_audio_thumbnail = new PublicAudioAudioThumbnail;
        $public_audio_audio_thumbnail->audio_id = $audio_id;
        $public_audio_audio_thumbnail->save();
      }

      // オーディオサムネの保存とDB登録
      if($isExistsAudio && $isExistsAudioThumbnail = checkFile($request, 'audio-thumbnail')){
        $audioThumbnailFileDatas = storeFileAndcreateDataForDb($request ,'audio-thumbnail');
        $audio_thumbnail_id = AudioUtil::saveAudioThumbnailData($audioThumbnailFileDatas);
        // オーディオとサムネイルの中間テーブルにもデータを保存
        $public_audio_audio_thumbnail = PublicAudioAudioThumbnail::where('audio_id', $audio_id)->first();
        $public_audio_audio_thumbnail->audio_thumbnail_id = $audio_thumbnail_id;
        $public_audio_audio_thumbnail->save();
      }

      return view('upload.publicFile');
    }

}