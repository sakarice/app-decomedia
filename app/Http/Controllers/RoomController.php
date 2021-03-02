<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Storage;
use App\Lib\EditTrack;
use App\Lib\EditRoom;
use App\Models\User;
use App\Models\Track;
use App\Models\Room;
use App\Models\RoomTrack;


class RoomController extends Controller
{
    // Roomを作成する
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
        
        $room_id = $room->id; // room_tracksテーブルへ登録するroom_idを取得しておく
        
        // room_tracksテーブルへデータ登録
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
    

    // Roomを削除する
    public function deleteRoom(Request $request){
        $user_id = Auth::user()->id;
        $room_id = $_POST['room_id'];
        // room_tracksテーブルから対象Roomのデータを削除
        DB::select('delete rt from room_tracks rt inner join rooms r on rt.room_id = r.id where r.user_id = '.$user_id.' and r.id = '.$room_id);
        // roomsテーブルから対象Roomのデータを削除
        Room::where('user_id', $user_id)->where('id', $room_id)->delete();

        $trackUrlAndTitles = EditTrack::getUserTrackData(5);
        $roomInfos = EditRoom::getMyRooms(3); // id,title,サムネ画像urlを取得
        $data = [
            'login_user_record' => User::find(Auth::id()),
            'trackUrlAndTitles' => $trackUrlAndTitles,
            'roomInfos' => $roomInfos
        ];

        return view('mypage.view', $data);
    }

    // 自分のRoomに入る
    public function showRoom(Request $request){
        $user_id = Auth::user()->id;
        $room_id = $_POST['room_id'];

        // (※後で追加する)セキュリティ強化のため,sessionに保存したuserかつroom_idと一致するかチェック

        // Room情報を取得
        $room = Room::where('user_id', $user_id)->where('id', $room_id)->first();
        $room_title = $room->title;
        $room_tracks = $room->tracks;
        $my_tracks = Track::where('user_id', $user_id)->get();

        $data = [
            'room_id'       => $room_id,
            'room_title'    => $room_title,
            'room_tracks'   => $room_tracks, //roomのtrack情報をモデルごと渡す
            'my_tracks'     => $my_tracks   // ユーザのtrack情報をモデルごと渡す
                ];
        // Room情報とTrack情報をviewに渡す
        return view('rooms.view', $data);
    }

    // 自分のRoomを更新
    public function updateRoom(Request $request){
        $user_id = Auth::user()->id;
        $room_id = $request->input('room_id');
        $room_title = $request->input('room_title');
        $track_ids = $request->input('track_ids');

        // Roomタイトルを更新
        Room::where('user_id', $user_id)
            ->where('id', $room_id)
            ->update(['title' => $room_title]);

        // room_tracksテーブルから対象Roomのレコードを削除
        RoomTrack::where('room_id', $room_id)->delete();

        // room_tracksテーブルに新しいTrack情報を追加
        foreach($track_ids as $index => $track_id){
            $room_tracks = new RoomTrack();
            $room_tracks->room_id = $room_id;
            $room_tracks->track_id = $track_id;
            $room_tracks->track_seq = $index + 1;
            \Log::info($track_id);
            $room_tracks->save();
        }

        return response(true);

    }

    // ajaxで受け取ったtrack_idに紐づくトラックのURLを返す
    public function getTrackInfo(Request $request){
        $user_id = Auth::user()->id;
        $track = Track::where('user_id', $user_id)
                        ->where('id', $request->input('track_id'))
                        ->first();
        $img_url = $track->img_url;
        $sound_url = $track->sound_url;

        // return response($track);
        return response()->json([
            'img_url'=>$img_url,
            'sound_url'=>$sound_url
            ]);
    }

    // 誰かのRoomに入る
    public function enterRoom(Request $request){
        $room_id = $_POST['room_id'];

        // (※後で追加する)セキュリティ強化のため,sessionに保存したuserかつroom_idと一致するかチェック

        // Room情報を取得
        $room = Room::where('id', $room_id)->first();
        $room_title = $room->title;
        $room_tracks = $room->tracks;

        $data = [
            'room_id'       => $room_id,
            'room_title'    => $room_title,
            'room_tracks'   => $room_tracks, //roomのtrack情報をモデルごと渡す
                ];
        // Room情報とTrack情報をviewに渡す
        return view('rooms.enter', $data);
    }



}
