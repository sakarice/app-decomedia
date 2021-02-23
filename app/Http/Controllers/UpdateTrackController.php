<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\EditTrack;
use App\Models\User;
use App\Models\Track;
use Storage;
use DB;

class UpdateTrackController extends Controller
{
    //
    public function view(Request $request){
        $user_id = Auth::user()->id;
        $track_id = $request->input('track_id');
        $track_model = Track::where('user_id', $user_id)->where('id', $track_id)->first();
        $track_title = $track_model->title;
        $img_path = $track_model->img_path;
        $img_url = $track_model->img_url;
        $data = [
            'msg' => $track_id,
            'track_title' => $track_title,
            'img_url' => $img_url
        ];

        // トラックIDをセッションに保存
        $request->session()->put('track_id', $track_id);

        return view('edittrack.update', $data);

    }

    public function update(Request $request){

        $user_id = Auth::user()->id;
        $track_id = $request->session()->get('track_id');
        
        // 1. 更新するトラックのファイルパスを先に取得しておく。(古いファイルは最後にS3から消すため)
        $track_model = Track::where('user_id', $user_id)->where('id', $track_id)->first();
        $old_sound_path = $track_model->sound_path;
        $old_img_path = $track_model->img_path;
        
        // 2. 新しいファイルをS3に登録 & DB登録用のパスを取得
        $imgfile = $request->file('img');
        $audiofile = $request->file('audio');
        $new_sound_path = EditTrack::saveTrackInS3($audiofile);
        $new_img_path = EditTrack::saveTrackInS3($imgfile);

        // 3. DBのレコードを更新(トラックタイトル、音楽ファイルパス、画像パス)
        $new_title = $request->input('sound-title-in-hidden');

        $track_model->title = $new_title;
        $track_model->sound_path = $new_sound_path;
        $track_model->img_path = $new_img_path;
        $track_model->sound_url = Storage::disk('s3')->url($new_sound_path);
        $track_model->img_url = Storage::disk('s3')->url($new_img_path);
        $track_model->save();

        // 4. S3から古いファイルを削除
        Storage::disk('s3')->delete($old_img_path);
        Storage::disk('s3')->delete($old_sound_path);

        // 5. sessionに保存していたトラックIDを破棄
        $request->session()->forget('track_id');

        // 6. トラック一覧へ遷移
        $data = [
            'trackDatas' => EditTrack::getUserTrackData()
        ];
        return view('tracks.view', $data);
    }


}
