<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Track;
use Storage;

class CreateTrackController extends Controller
{
    //
    public function index() {
        if(Auth::check()){
            $checked = "ユーザー：".Auth::user()->name."は認証済みです";
            $data = [
                'msg' => $checked,
            ];
            return view('edittrack.create', $data);
        } else {
            return view('auth.login');
            // $checked = "ユーザー：".Auth::user()->name."は認証されていません";
        }
    }


    public function uploadfile(Request $request) {
        
        // tracksテーブルへデータを保存
        $tracks = new Track();
        $tracks->user_id = Auth::user()->id;
        $tracks->title = $request->input('sound-title-in-hidden');
        $tracks->sound_name = $this->saveFileInS3($request, 'audio');
        $tracks->img_name = $this->saveFileInS3($request, 'img');

        $tracks->save();

        return view('edittrack.create', ['msg'=> Storage::disk('s3')->url(Track::latest()->first()->img_name)]);
    }
    
    private function saveFileInS3($request, $fileType){
        $file = $request->file($fileType);
        $fileName = $file->getClientOriginalName();

        // AWSのS3へファイルを保存
        $path = Storage::disk('s3')->putFile('track/'.$fileType, $file, 'public');        

        // AWSへ保存したファイル名を返す
        return $path;
    }


}
