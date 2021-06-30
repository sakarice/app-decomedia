<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Room\RoomController;
use App\Models\User;
use App\Models\Room;
use App\Models\Roomlist;
use App\Lib\EditRoom;
use App\Lib\RoomUtil;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $roomPreviewInfos = array();
        $rooms = Room::inRandomOrder()->take(20)->get();
        foreach($rooms as $index => $room){
            $room_id = $room->id;
            $roomPreviewInfos[] = RoomUtil::getRoomPreviewInfo($room_id);
        }
        
        $data = [
            'roomPreviewInfos' => $roomPreviewInfos
        ];

        return view('home2', $data);
    }
}
