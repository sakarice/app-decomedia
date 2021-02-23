<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Track;
use App\Models\Room;
use App\Lib\EditTrack;
use App\Lib\EditRoom;

use Storage;

class DeleteRoomController extends Controller
{
    //
    public function deleteRoom(Request $request){
        \Log::info($_POST['room_id']);
        $user_id = Auth::user()->id;
        $room_id = $_POST['room_id'];
        // room_tracksテーブルから対象Roomのデータを削除
        DB::select('delete rt from room_tracks rt inner join rooms r on rt.room_id = r.id where r.user_id = '.$user_id.' and r.id = '.$room_id);
        // roomsテーブルから対象Roomのデータを削除
        Room::where('user_id', $user_id)->where('id', $room_id)->delete();

        $trackUrlAndTitles = EditTrack::getUserTrackData(5);
        $roomInfos = EditRoom::getUserRoomInfo(3); // id,title,サムネ画像urlを取得
        $data = [
            'login_user_record' => User::find(Auth::id()),
            'trackUrlAndTitles' => $trackUrlAndTitles,
            'roomInfos' => $roomInfos
        ];

        return view('mypage.view', $data);

    }
}
