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
        $getInfoNum = 5;
        $data = [
            'createdMediaPreviewInfos' => MediaUtil::getCreatedMediaPreviewInfos($getInfoNum)['createdMediaPreviewInfos'],
            'likedMediaPreviewInfos' => MediaUtil::getLikedMediaPreviewInfos($getInfoNum)['likedMediaPreviewInfos'],
        ];

        return view('mypage.view', $data);
    }

}
