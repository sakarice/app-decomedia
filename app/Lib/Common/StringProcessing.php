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

class StringProcessing
{
    // 拡張子を除いたファイル名を取得する
    // ※実態は↓の関数を複合した処理
    public static function getFilenameExceptExt($filename){
      // ファイル名から拡張子を抽出する
      // ※実際にはファイル名末尾に一番近い「.」より後を抽出しているだけなので、
      //   .より後が拡張子ではない可能性もある。↓のcheckContentsExt関数でチェックすること
      function getExt($filename){
        $pos = strrpos($filename, '.');
        if($pos !== false){
          \Log::info(mb_substr($filename, $pos-1));
          return mb_substr($filename, $pos-1);
        } else {
          return '';
        }
      }

      // ファイル名末尾の拡張子を削除する
      // ※実際にはファイル名末尾に一番近い「.」より前を抽出しているだけなので、
      //   .より後が拡張子ではない可能性もある。↓のcheckContentsExt関数でチェックすること
      function delExt($filename) {
        $pos = strrpos($filename, '.');
        if($pos !== false){
          return mb_substr($filename, 0, $pos-2);
        } else {
          return '';
        }
      }
      
      // 引き数で渡した拡張子が画像、音楽、動画の拡張子であるかをチェックする
      function checkContentsExt($ext){
        // チェック対象拡張子を配列で定義。
        $imgExt = array('jpg', 'jpeg', 'png');
        $audioExt = array('mp3', 'wav', 'aac', 'flac', 'aiff');
        $movieExt = array('mp4', 'avi', 'mpeg', 'wmv');
        $contentsExt = array_merge($imgExt, $audioExt, $movieExt);

        // チェック用に引き数の拡張子文字列を小文字に整形
        $lowerExt = strtolower($ext);

        // チェック処理
        $isContentsExt = in_array($lowerExt, $contentsExt);

        return $isContentsExt;
      }


      if(checkContentsExt(getExt($filename))){
        return delExt($filename);
      } else {// 拡張子が無い、もしくはチェック対象拡張子でない場合は、ファイル名をそのまま返す
        return $filename;
      }

    }


}