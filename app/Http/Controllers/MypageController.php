<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Media;
use App\Models\Medialist;
use App\Lib\EditMedia;
use App\Lib\MediaUtil;

use Storage;

class MypageController extends Controller
{
    public function view(){
        $data = [
            'createdMediaPreviewInfos' => MediaUtil::getCreatedMediaPreviewInfos(5)['createdMediaPreviewInfos'],
            'likedMediaPreviewInfos' => MediaUtil::getLikedMediaPreviewInfos(5)['likedMediaPreviewInfos'],
        ];

        return view('mypage.view', $data);
    }

    // // プロフィール画面へユーザ情報を渡す
    // public function profile(Request $request){
    //     $user_id = Auth::user()->id;
    //     $user = User::find($user_id);
    //     $data = [
    //         'user' => $user
    //     ];
    //     return view ('profile.view', $data);
    // }

}
