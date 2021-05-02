<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
use App\Lib\SaveFile;

use Storage;

class Functions extends Controller
{

    // デフォルトファイルアップロード用viewを表示
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

      function storeFileAndcreateDataForDb($request, $type){
        $fileName = $request->file($type)->getClientOriginalName();
        $filePath = SaveFile::saveDefaultFileInS3($request, $type);
        $fileUrl = Storage::disk('s3')->url($filePath);
        
        $fileDatas = array (
          'owner_user_id' => NULL,
          'name' => $fileName,
          'path' => $filePath,
          'url' => $fileUrl
        );
        return $fileDatas;
      }

      // 画像ファイルの保存とDB登録
      if(checkFile($request, 'img')){
        $imgFileDatas = storeFileAndcreateDataForDb($request ,'img');
        saveFile::saveImgDataInDB($imgFileDatas);
      }
      
      // オーディオファイルの保存とDB登録
      if(checkFile($request, 'audio')){
        $audioFileDatas = storeFileAndcreateDataForDb($request ,'audio');
        if(checkFile($request, 'audio-thumbnail')){
          $audioThumbnailFileDatas = storeFileAndcreateDataForDb($request ,'audio-thumbnail');
          $thumbnailPath = $audioThumbnailFileDatas['path'];
          $thumbnailUrl = $audioThumbnailFileDatas['url'];
          $audioFileDatas += array('thumbnail_path' => $thumbnailPath);
          $audioFileDatas += array('thumbnail_url' => $thumbnailUrl);
        }
        saveFile::saveAudioDataInDB($audioFileDatas);
      }


      // $fileNames = [
      //   // 'imgFileUrl' => $imgFileDatas['url'],
      //   // 'audioFileName' => $audioFileName,
      //   // 'audioThumbnailFileName' => $audioThumbnailFileName
      // ];

      return view('upload.defaultFile');
    }

}