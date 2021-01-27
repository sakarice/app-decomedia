<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Track;
use Storage;

class MypageController extends Controller
{
    public function view(){

        // トラックのタイトルと画像表示用のURLを連想配列として格納
        // プレビューなので5トラックくらいで十分。全量はtracksページで表示。
        $authenticated_userId = Auth::user()->id;
        $tracks = Track::limit(5)->where('user_id', $authenticated_userId)->get();
        $trackUrlAndTitles = array();
        foreach($tracks as $track){
            $trackUrlAndTitle = array(
                'url' => Storage::disk('s3')->url($track->img_name),
                'title' => $track->title
            );

            $trackUrlAndTitles[] = $trackUrlAndTitle;
        }

        $data = [
            'login_user_record' => User::find(Auth::id()),
            'trackUrlAndTitles' => $trackUrlAndTitles
        ];

        return view('mypage.view', $data);
    }
}
