<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MediaMovieController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaMovie;
use Storage;

class MediaMovieUtil
{

  // 3.store // Media動画情報をDBに保存
  public static function saveMediaMovieData($media_id, $request){
    $user_id = Auth::user()->id;
    $mediaMovie = new MediaMovie();
    $mediaMovie->user_id = $user_id;
    $mediaMovie->media_id = $media_id;
    $mediaMovie->video_id = $request->movie['videoId'];
    $mediaMovie->width = $request->movie['width'];
    $mediaMovie->height = $request->movie['height'];
    $mediaMovie->isLoop = $request->movie['isLoop'];
    $mediaMovie->movie_layer = $request->movie['layer'];
    $mediaMovie->save();
  }

  // 4.show 
  // Media動画の情報を取得(Media作成、編集、閲覧時に使用)
  public static function getMediaMovieData($media_id){
      if(MediaMovie::where('media_id', $media_id)->exists()){
        $media_movie = MediaMovie::where('media_id', $media_id)->first();            
        $media_movie_data = [
          'videoId' => $media_movie->video_id,
          'width' => $media_movie->width,
          'height' => $media_movie->height,
          'isLoop' => $media_movie->isLoop,
          'layer' => $media_movie->movie_layer,
        ];
      } else { // DBに動画設定が保存されていなければ、デフォルト値を設定
        $media_movie_data = [
          'videoId' => "",
          'width' => 400,
          'height' => 300,
          'isLoop' => false,
          'layer' => 1,
        ];        
      }
      return $media_movie_data;
  }

  // 6.update
  public static function updateMediaMovieData($media_id, $request){
    $mediaMovie;
    if(MediaMovie::where('media_id', $media_id)->exists()){
      $mediaMovie = MediaMovie::where('media_id', $media_id)->first();
    } else {
      $mediaMovie = new MediaMovie;
      $mediaMovie->user_id = Auth::user()->id;
      $mediaMovie->media_id = $media_id;
    }
    $mediaMovie->video_id = $request->movie['videoId'];
    $mediaMovie->width = $request->movie['width'];
    $mediaMovie->height = $request->movie['height'];
    $mediaMovie->isLoop = $request->movie['isLoop'];
    $mediaMovie->movie_layer = $request->movie['layer'];
    $mediaMovie->save();
  }




}