<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Track;
use Storage;

class EditTrack
{

  // トラック一覧表示用：トラックの情報(id,画像URL,タイトル)を連想配列で返す
  public static function getUserTrackData($max_num=100){
    $authenticated_userId = Auth::user()->id;
    $tracks = Track::limit($max_num)->where('user_id', $authenticated_userId)->get();
    $trackDatas = array();
    foreach($tracks as $track){
        $trackData = array(
            'id' => $track->id,
            'title' => $track->title,
            'url' => $track->img_url
        );

        $trackDatas[] = $trackData;
    }
    return $trackDatas;
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


  public static function saveTrackInS3($file){
    // ファイルタイプに応じたS3の保存先ディレクトリを設定
    $directry;
    switch(true){
      case preg_match('/^image/', $file->getMimeType()):
        $directry = "track/img";
        break;
      case preg_match('/^audio/', $file->getMimeType()): 
        $directry = "track/audio";
        break;
    }
    // S3へファイル保存 & DBへ登録する保存先パスを取得
    $filePath = Storage::disk('s3')->putFile($directry, $file, 'public');
    return $filePath;
  }


  // DBにトラック情報を保存する。
  public static function saveTrackInfoInDB($trackInfo){
    // DBへtrack情報を登録
    $tracks = new Track();

    $tracks->user_id    = $trackInfo['user_id'];
    $tracks->title      = $trackInfo['title'];
    $tracks->sound_path = $trackInfo['sound_path']; // S3へ保存した音声ファイルのパス
    $tracks->img_path   = $trackInfo['img_path'];   // S3へ保存した画像ファイルのパス
    $tracks->sound_url = $trackInfo['sound_url']; // S3へ保存した音声ファイルのURL
    $tracks->img_url   = $trackInfo['img_url'];   // S3へ保存した画像ファイルのURL
  
    $tracks->save();
  }

  // S3から指定されたトラックのファイルを削除する。
  public static function deleteTrackFileFromS3($user_id, $track_ids){
    $tracks = Track::where('user_id', $user_id)->whereIn('id', $track_ids)->get();

    foreach($tracks as $track){
      // S3:バックアップ
      Storage::disk('s3')->copy($track->img_path, 'bk/'.$track->img_path);
      Storage::disk('s3')->copy($track->sound_path, 'bk/'.$track->sound_path);
      // S3:削除
      Storage::disk('s3')->delete($track->img_path);
      Storage::disk('s3')->delete($track->sound_path);
    }
  }

          

}