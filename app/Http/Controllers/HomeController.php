<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Room\RoomController;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;
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
        // $this->middleware('auth' , ['except' => 'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $roomPreviewInfos = array();
        $getNum = 20; // 取得するRoomプレビュー情報件数
        // 公開中のRoomのみ取得
        $openRoomSettings = RoomSetting::where('open_state', true)->inRandomOrder()->take($getNum)->get();
        // $rooms = Room::inRandomOrder()->take($getNum)->get();
        foreach($openRoomSettings as $index => $openRoomSetting){
            $room_id = $openRoomSetting->room_id;
            $roomPreviewInfos[] = RoomUtil::getRoomPreviewInfo($room_id);
        }

        // ログイン、ログアウト、サインアップの表示/非表示      
        $data = [
            'isLogin' => Auth::check(),
            'roomPreviewInfos' => $roomPreviewInfos
        ];

        return view('home', $data);
    }
}
