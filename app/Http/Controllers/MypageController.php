<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\Roomlist;
use App\Lib\EditRoom;

use Storage;

class MypageController extends Controller
{
    public function view(){

        $authenticated_userId = Auth::user()->id;

        $roomInfos = EditRoom::getMyRooms(6); // id,title,サムネ画像urlを取得

        $data = [
            'roomInfos' => $roomInfos
        ];

        return view('mypage.view', $data);
    }

    // プロフィール画面へユーザ情報を渡す
    public function profile(Request $request){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $data = [
            'user' => $user
        ];
        return view ('profile.view', $data);

    }
}
