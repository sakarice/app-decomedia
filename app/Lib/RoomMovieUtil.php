<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RoomMovieController;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomMovie;
use Storage;

class RoomMovieUtil
{

  // 3.store // Room動画情報をDBに保存
  public static function saveRoomMovieData($room_id, $request){
    $user_id = Auth::user()->id;
    $roomMovie = new RoomMovie();
    $roomMovie->user_id = $user_id;
    $roomMovie->room_id = $room_id;
    $roomMovie->video_id = $request->movie['videoId'];
    $roomMovie->width = $request->movie['width'];
    $roomMovie->height = $request->movie['height'];
    $roomMovie->isLoop = $request->movie['isLoop'];
    $roomMovie->movie_layer = $request->movie['layer'];
    $roomMovie->save();
  }

  // 4.show 
  // Room動画の情報を取得(Room作成、編集、閲覧時に使用)
  public static function getRoomMovieData($room_id){
      if(RoomMovie::where('room_id', $room_id)->exists()){
        $room_movie = RoomMovie::where('room_id', $room_id)->first();            
        $room_movie_data = [
          'videoId' => $room_movie->video_id,
          'width' => $room_movie->width,
          'height' => $room_movie->height,
          'isLoop' => $room_movie->isLoop,
          'layer' => $room_movie->movie_layer,
        ];
      } else { // DBに動画設定が保存されていなければ、デフォルト値を設定
        $room_movie_data = [
          'videoId' => "",
          'width' => 400,
          'height' => 300,
          'isLoop' => false,
          'layer' => 1,
        ];        
      }
      return $room_movie_data;
  }

  // 6.update
  public static function updateRoomMovieData($room_id, $request){
    $roomMovie;
    if(RoomMovie::where('room_id', $room_id)->exists()){
      $roomMovie = RoomMovie::where('room_id', $room_id)->first();
    } else {
      $roomMovie = new RoomMovie;
      $roomMovie->user_id = Auth::user()->id;
      $roomMovie->room_id = $room_id;
    }
    $roomMovie->video_id = $request->movie['videoId'];
    $roomMovie->width = $request->movie['width'];
    $roomMovie->height = $request->movie['height'];
    $roomMovie->isLoop = $request->movie['isLoop'];
    $roomMovie->movie_layer = $request->movie['layer'];
    $roomMovie->save();
  }




}