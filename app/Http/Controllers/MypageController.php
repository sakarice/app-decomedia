<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Track;
use App\Models\Room;
use App\Lib\EditTrack;
use App\Lib\EditRoom;

use Storage;

class MypageController extends Controller
{
    public function view(){

        // トラックのタイトルと画像表示用のURLを連想配列として格納
        // プレビューなので5トラックくらいで十分。全量はtracksページで表示。
        $authenticated_userId = Auth::user()->id;

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
