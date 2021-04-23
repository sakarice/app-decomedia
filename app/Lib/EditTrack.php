<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use Storage;

class EditRoom
{

  // トラック一覧表示用：トラックの情報(id,画像URL,タイトル)を連想配列で返す
  public static function getUserRoomData($max_num=100){
    $authenticated_userId = Auth::user()->id;
    $rooms = Room::limit($max_num)->where('user_id', $authenticated_userId)->get();
    $roomDatas = array();
    foreach($rooms as $room){
        $roomData = array(
            'id' => $room->id,
            'name' => $room->name,
            'url' => $room->img_url
        );

        $roomDatas[] = $roomData;
    }
    return $roomDatas;
  }


  // ファイルのMimetypeを判定する。
  public static function judgeMimetypeFront($file){
    $tmpFilePath = $file['tmp_name'];
    $mime = shell_exec('file -bi '.escapeshellcmd($tmpFilePath)); // LinuxのMIMEコマンドでMIMEタイプ判定
    $mime = trim($mime);  // 改行コードを除去
    $mime = preg_replace("/ [^ ]*/", "", $mime);  // MIMEタイプが2つあったり、文字コードが含まれている場合があるので、空白以降を除去
    $mime_front = substr($mime, 0, strpos($mime, "/"));
    return $mime_front;
  }


  public static function saveroomInS3($file){
    // ファイルタイプに応じたS3の保存先ディレクトリを設定
    $directry;
    switch(true){
      case preg_match('/^image/', $file->getMimeType()):
        $directry = "room/img";
        break;
      case preg_match('/^audio/', $file->getMimeType()): 
        $directry = "room/audio";
        break;
    }
    // S3へファイル保存 & DBへ登録する保存先パスを取得
    $filePath = Storage::disk('s3')->putFile($directry, $file, 'public');
    return $filePath;
  }


  // DBにトラック情報を保存する。
  public static function saveroomInfoInDB($roomInfo){
    // DBへroom情報を登録
    $rooms = new room();

    $rooms->user_id    = $roomInfo['user_id'];
    $rooms->name      = $roomInfo['name'];
    $rooms->sound_path = $roomInfo['sound_path']; // S3へ保存した音声ファイルのパス
    $rooms->img_path   = $roomInfo['img_path'];   // S3へ保存した画像ファイルのパス
    $rooms->sound_url = $roomInfo['sound_url']; // S3へ保存した音声ファイルのURL
    $rooms->img_url   = $roomInfo['img_url'];   // S3へ保存した画像ファイルのURL
  
    $rooms->save();
  }

  // S3から指定されたトラックのファイルを削除する。
  public static function deleteroomFileFromS3($user_id, $room_ids){
    $rooms = room::where('user_id', $user_id)->whereIn('id', $room_ids)->get();

    foreach($rooms as $room){
      // S3:バックアップ
      Storage::disk('s3')->copy($room->img_path, 'bk/'.$room->img_path);
      Storage::disk('s3')->copy($room->sound_path, 'bk/'.$room->sound_path);
      // S3:削除
      Storage::disk('s3')->delete($room->img_path);
      Storage::disk('s3')->delete($room->sound_path);
    }
  }

          

}