<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Lib\EditTrack;
use App\Lib\EditRoom;
use App\Models\User;
use App\Models\Track;
use App\Models\Room;
use App\Models\RoomTrack;

class SearchController extends Controller
{
    //
    public function searchRooms(Request $request){
        $user_id = Auth::user()->id;
        $keyword = $request->input('keyword');
        if(!empty($keyword)){
            $rooms = Room::where('title', 'LIKE', "%$keyword%")->get();
        }
        $data = [
            'keyword' => $keyword,
            'rooms' => $rooms
        ];


        return view('searchresult.view', $data);
    }
}
