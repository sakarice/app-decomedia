<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Storage;
use App\Lib\EditTrack;
use App\Models\User;
use App\Models\Track;
// use App\Models\EditTrack;

class TrackController extends Controller
{
    public function view(){
        // トラックの情報(id,画像URL,タイトル)を連想配列でビューに渡す
        $data = [
            'trackDatas' => EditTrack::getUserTrackData()
        ];
        return view('tracks.view', $data);
    }

    // ■トラックデータをDBとS3から削除
    public function deleteTrack(Request $request){
        $user_id = Auth::user()->id;
        $track_ids = $request['selected-track-ids'];

        // S3:画像、音声ファイルを削除
        EditTrack::deleteTrackFileFromS3($user_id, $track_ids);

        // room_tracksテーブルからデータを削除
        $track_ids_text = "(".implode("," , $track_ids).")";
        DB::select('delete rt from room_tracks rt inner join tracks t on rt.track_id = t.id where t.user_id = '.$user_id.' and t.id in '.$track_ids_text);

        // tracksテーブルからデータ削除
        $tracks = Track::where('user_id', $user_id)->whereIn('id', $track_ids)->get();
        foreach($tracks as $track){
            $track->delete();
        }

        // viewファンクションと同じ処理
        // トラックの情報(id,画像URL,タイトル)を連想配列でビューに渡す
        $data = [
            'trackDatas' => EditTrack::getUserTrackData()
        ];
        return view('tracks.view', $data);

    }


}
