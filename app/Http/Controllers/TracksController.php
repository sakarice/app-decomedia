<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  
use Storage;
use App\Models\User;
use App\Models\Track;

class TracksController extends Controller
{
    public function view(){
        // トラックの情報(id,画像URL,タイトル)を連想配列でビューに渡す
        $data = [
            'trackDatas' => $this->getTrackData(),
        ];
        return view('tracks.view', $data);
    }

    // ■トラックデータをDBとS3から削除
    public function deleteTrack(Request $request){
        // 削除対象のモデルを取得
        $user_id = Auth::user()->id;
        $track_ids = $request['selected-track'];
        $tracks = Track::where('user_id', $user_id)->whereIn('id', $track_ids)->get();

        // 画像、音声ファイルをS3から削除
        $this->delImgAndSoundFromS3($tracks);

        // ●DB
        // DBから削除対象レコードを削除
        foreach($tracks as $track){
            $track->delete();
        }

        // viewファンクションと同じ処理
        // トラックの情報(id,画像URL,タイトル)を連想配列でビューに渡す
        $data = [
            'trackDatas' => $this->getTrackData(),
        ];
        return view('tracks.view', $data);

    }

    // S3から画像、音声ファイルをバックアップを取ってから削除する
    private function delImgAndSoundFromS3($tracks){
        foreach($tracks as $track){
            // バックアップ
            Storage::disk('s3')->copy($track->img_name, 'bk/'.$track->img_name);
            Storage::disk('s3')->copy($track->sound_name, 'bk/'.$track->sound_name);
            // 削除
            Storage::disk('s3')->delete($track->img_name);
            Storage::disk('s3')->delete($track->sound_name);
        }
    }



    // トラックの情報(id,画像URL,タイトル)を連想配列で返す
    private function getTrackData(){
        $authenticated_userId = Auth::user()->id;
        $tracks = Track::where('user_id', $authenticated_userId)->get();
        $trackDatas = array();
        foreach($tracks as $track){
            $trackData = array(
                'id' => $track->id,
                'title' => $track->title,
                'url' => Storage::disk('s3')->url($track->img_name)
            );

            $trackDatas[] = $trackData;
        }
        return $trackDatas;
    }

}
