<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomMovie;
use Storage;

class MovieController extends Controller
{
    //
    public static function getRoomMovieData($room_id){
        $room_movie_data = array();
        if(RoomMovie::where('room_id', $room_id)->exists()){
            $room_movie = RoomMovie::where('room_id', $room_id)->first();            
            // room画像情報を格納した連想配列を作成
            $room_movie_data = [
                'videoId' => $room_movie->video_id,
                'width' => $room_movie->width,
                'height' => $room_movie->height,
                'isLoop' => $room_movie->isLoop,
                'layer' => $room_movie->movie_layer,
            ];
        }

        return $room_movie_data;

    }
}
