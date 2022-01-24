<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Lib\Common\StringProcessing;
use Storage;

class StoreFileInS3
{

  public static function userOwnMediaFile($user_id, $file){
    // ファイルタイプに応じたS3の保存先ディレクトリを設定
    $directory;
    switch(true){
      case preg_match('/^image/', $file->getMimeType()):
        $directory = "users/".$user_id."/img/media";
        break;
      case preg_match('/^audio/', $file->getMimeType()):
        $directory = "users/".$user_id."/audio/media";
        break;  
    }
    // S3へファイル保存 & DBへ登録する保存先パスを取得
    $filePath = Storage::disk('s3')->putFile($directory, $file, 'public');
    return $filePath;
  }

  public static function judgeS3StoreDirectoryFromFileType($fileType){
    // ファイルの内容に応じた保存先ディレクトリを指定
    switch($fileType){
      case 'img':
        $directory = "public/img/media";
        break;
      case 'audio':
        $directory = "public/audio/media";
        break;
      case 'audio-thumbnail':
        $directory = "public/img/audio_thumbnail";
        break;
    }
    return $directory;
  }


  // s3にファイルを保存し、保存先パスと保存ファイル名を返す
  // ファイル名は既に保存済みのものと重複しないよう適宜インデックスを付ける
  public static function storePublicFileInS3($file, $directory){
    $original_file_name = $file->getClientOriginalName();
    // $ext = StringProcessing::getExtFromFileName($original_file_name);
    // $ext_excepted_file_name = StringProcessing::getFilenameExceptExt($original_file_name);
    $file_name = StoreFileInS3::addIndexToFileName($original_file_name, $directory);
    // $filePath = Storage::disk('s3')->putFile($directory, $file, 'public');
    $just_stored_file_path = $file->storeAs($directory, $file_name, 's3');
    $just_stored_file_name = substr($just_stored_file_path, strlen($directory)+1);
    return [$just_stored_file_path, $just_stored_file_name];
  }

  // 同パス内の同ファイル名で始まるファイル数+1のインデックスを付与する
  // (※同名のファイルが無ければインデックスをつけない)
  public static function addIndexToFileName($file_name, $file_path){
    $stored_file_paths = Storage::disk('s3')->files($file_path);
    $file_path_length = strlen($file_path);
    $stored_file_names = array();
    foreach($stored_file_paths as $stored_file_path){
      $stored_file_names[] = substr($stored_file_path, $file_path_length+1);
    }
    $ext = StringProcessing::getExtFromFileName($file_name);
    $ext_excepted_file_name = StringProcessing::getFilenameExceptExt($file_name);
    
    $stored_same_files = preg_grep('/^'.$file_name.''.'\z/', $stored_file_names);
    $stored_indexed_files = preg_grep('/^'.$ext_excepted_file_name.'_'.'/', $stored_file_names);
    
    $count_files = $stored_same_files + $stored_indexed_files;
    $stored_same_name_file_num = count($count_files);
    $file_name_with_index = $ext_excepted_file_name."_".($stored_same_name_file_num+1).".".$ext;
    $return_file_name = $stored_same_name_file_num > 0 ? $file_name_with_index : $file_name;
    return $return_file_name;
  }


}