<?php

namespace App\Lib\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\ImgUtil;
use App\Lib\AudioUtil;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\DefaultBgm;
use App\Models\DefaultImg;
use App\Models\UserOwnBgm;
use App\Models\UserOwnImg;
use App\Models\Room;
use App\Models\RoomBgm;
use App\Models\RoomImg;
use App\Models\Roomlist;
use App\Models\RoomRoomlist;

use Storage;

class Functions
{

    public function view() {
      $fileNames = [
        'imgFileUrl' => "",
        'audioFileUrl' => "",
        'audioThumbnailFileUrl' => ""
      ];

      return view('upload.defaultFile', $fileNames);
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

        $fileName = $request->file($type)->getClientOriginalName();
        $filePath = StoreFileInS3::DefaultFile($request, $type);
        $fileUrl = Storage::disk('s3')->url($filePath);

        
        $fileDatas = array (
          'owner_user_id' => NULL,
          'name' => $fileName,
          // 'name' => $convertedText,
          'path' => $filePath,
          'url' => $fileUrl
        );
        return $fileDatas;
      }

      // 画像ファイルの保存とDB登録
      if(checkFile($request, 'img')){
        $imgFileDatas = storeFileAndcreateDataForDb($request ,'img');
        ImgUtil::saveImgData($imgFileDatas);
      }
      
      // オーディオファイルの保存とDB登録
      if(checkFile($request, 'audio')){
        $audioFileDatas = storeFileAndcreateDataForDb($request ,'audio');
        if(checkFile($request, 'audio-thumbnail')){
          $audioThumbnailFileDatas = storeFileAndcreateDataForDb($request ,'audio-thumbnail');
          $audioFileDatas += array('thumbnail_path' => $audioThumbnailFileDatas['path']);
          $audioFileDatas += array('thumbnail_url' => $audioThumbnailFileDatas['url']);
        }
        AudioUtil::saveAudioData($audioFileDatas);
      }

      return view('upload.defaultFile');
    }

}