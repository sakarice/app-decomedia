<?php

namespace App\Lib\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StringProcessing
{
    private static $imgExt = '\.jpg$|\.jpeg$|\.png$|\.gif$'; // 画像ファイルの拡張子を定義
    private static $audioExt = '\.wave$|\.aiff$|\.mp3$|\.m4a$|\.aac$|\.flac$'; // 音楽ファイルの拡張子を定義
    private static $movieExt = '\.mp4$|\.avi$|\.mov$|\.flv$'; // 動画ファイルの拡張子を定義

    // 拡張子を除いたファイル名を取得する
    public static function getFilenameExceptExt($filename){
      // 正規表現チェックのためにファイル名を小文字に整形
      $lowerFilename = strtolower($filename);

      // 削除対象の拡張子を定義
      $targetExt = '/'.self::$imgExt.'|'.self::$audioExt.'|'.self::$movieExt.'/i'; // 拡張子をまとめて検索用の正規表現にする
      
      // ヒットした拡張子を削除するために、変換先文字を空に
      $replace = ''; 

      // チェック処理
      $extExceptedFilename = preg_replace($targetExt, $replace, $lowerFilename);
      \Log::info('ファイル名の拡張子削除処理結果：'.$filename." ⇒ ".$extExceptedFilename);
      return $extExceptedFilename;
    }


    public static function getExtFromFileName($file_name){
      // 正規表現チェックのためにファイル名を小文字に整形
      $lowerFilename = strtolower($file_name);      
      // チェック処理
      preg_match('/[^.]+$/', $lowerFilename, $ext);
      // $ext = preg_replace($targetExt, $replace, $lowerFilename);
      \Log::info('ファイル名の拡張子取得処理結果：'.$file_name." ⇒ ".$ext[0]);
      return $ext[0];
    }


}