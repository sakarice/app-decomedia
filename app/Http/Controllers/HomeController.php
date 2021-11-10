<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Media\MediaController;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaSetting;
use App\Models\Medialist;
use App\Lib\EditMedia;
use App\Lib\MediaUtil;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth' , ['except' => 'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $env = app()->environment('AWS_BUCKET');
        \Log::info($env);
        $mediaPreviewInfos = array();
        $getNum = 20; // 取得するMediaプレビュー情報件数
        // 公開中のMediaのみ取得
        $openMediaSettings = MediaSetting::where('open_state', true)->inRandomOrder()->take($getNum)->get();
        // $medias = Media::inRandomOrder()->take($getNum)->get();
        foreach($openMediaSettings as $index => $openMediaSetting){
            $media_id = $openMediaSetting->media_id;
            $mediaPreviewInfos[] = MediaUtil::getMediaPreviewInfo($media_id);
        }

        // ログイン、ログアウト、サインアップの表示/非表示      
        $data = [
            'isLogin' => Auth::check(),
            'mediaPreviewInfos' => $mediaPreviewInfos
        ];

        return view('home', $data);
    }
}
