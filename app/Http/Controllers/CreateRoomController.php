<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Storage;
use App\Lib\EditTrack;
use App\Models\User;
use App\Models\Track;
use App\Models\Room;
use App\Models\RoomTrack;


class CreateRoomController extends Controller
{
    //
    public function createRoom(Request $request){
        // 選択されたトラックIDを取得
        $title = $_POST['room-title'];
        $track_ids = $_POST['selected-track-ids'];
        $user_id = Auth::user()->id;

        // Roomテーブルへデータ登録
        $thumbnail_path = Track::where('id', $track_ids[0])->first()->img_path; // 最初に選ばれたトラックの画像をサムネに
        $thumbnail_url = Storage::disk('s3')->url($thumbnail_path);

        $room = new Room();
        $room->user_id = $user_id;
        $room->title = $title;
        $room->thumbnail_path = $thumbnail_path;
        $room->thumbnail_url = $thumbnail_url;
        $room->save();
        
        $room_id = $room->id; // room_trackテーブルへ登録するroom_idを取得しておく
        
        // room_trackテーブルへデータ登録
        foreach($track_ids as $index => $track_id){
            $room_tracks = new RoomTrack();
            $room_tracks->room_id = $room_id;
            $room_tracks->track_id = $track_id;
            $room_tracks->track_seq = $index;
            \Log::info($track_id);
            $room_tracks->save();
        }

        $data = [
            'trackDatas' => EditTrack::getUserTrackData()
        ];
        return view('tracks.view', $data);


    }
}
