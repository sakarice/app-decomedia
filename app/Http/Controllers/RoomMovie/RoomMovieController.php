<?php

namespace App\Http\Controllers\RoomMovie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomMovie;
use Storage;

class RoomMovieController extends Controller
{
    // 1.index
    public function index(){}
    // 2.create
    public function create(Request $request){}
    // 3.store
    public static function store($room_id, $request){
        \Log::info('movie保存開始');
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
        \Log::info('動画保存完了');
    }

    // 4. show
    public static function show($room_id){
        // $room_movie_data = array();
        // 動画設定に初期値を設定
        $room_movie_data = [
            'videoId' => "",
            'width' => 400,
            'height' => 300,
            'isLoop' => false,
            'layer' => 1,
        ];
        // DBに動画設定が保存されていれば、DBの値で上書き
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

    
    // 5.edit
    public function edit($room_id){}
    // 6.update
    public function update($room_id){}
    // 7.destroy
    public static function destroy($room_id){
        RoomMovie::where('room_id', $room_id)->first()->delete();
    }

}
