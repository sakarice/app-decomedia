<?php

namespace App\Http\Controllers\RoomMovie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\RoomMovieUtil;
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
        RoomMovieUtil::saveRoomMovieData($room_id, $request);
    }

    // 4. show
    public static function show($room_id){
        $room_movie_data = RoomMovieUtil::getRoomMovieData($room_id);
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
