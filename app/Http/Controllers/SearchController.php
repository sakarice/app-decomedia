<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Controllers\Room\RoomController;
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
        $rooms;
        if(!empty($keyword)){
            $rooms = Room::where('name', 'LIKE', "%$keyword%")->get();
        }
        $roomPreviewInfos = RoomController::getRoomPreviewInfos($rooms);

        $data = [
            'keyword' => $keyword,
            'roomPreviewInfos' => $roomPreviewInfos
        ];


        return view('searchResult.view', $data);
    }
}
