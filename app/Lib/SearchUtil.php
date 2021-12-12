<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use App\Http\Controllers\MediaController;
use App\Lib\MediaUtil;
use App\Models\User;
use App\Models\Media;
use App\Models\MediaSetting;

class SearchUtil
{
    // 検索結果に表示するmediaの最大数
    private static $maxGetNum = 20;

    public static function searchMedias(Request $request){
        $keyword = $request->input('keyword');
        $mediaPreviewInfos = array();
        if(!empty($keyword)){
            $media_settings
             = MediaSetting::where('open_state', true)
                ->where('name', 'LIKE', "%$keyword%")
                ->take(self::$maxGetNum)
                ->get();
        } else {
            $media_settings = MediaSetting::where('open_state', true)
                ->inRandomOrder()
                ->take(self::$maxGetNum)
                ->get();
        }
        foreach($media_settings as $media_setting){
            $media_id = $media_setting->media_id;
            $mediaPreviewInfos[] = MediaUtil::getMediaPreviewInfo($media_id);
        }

        $data = [
            'isLogin' => Auth::check(),
            'keyword' => $keyword,
            'mediaPreviewInfos' => $mediaPreviewInfos
        ];

        return view('search.view', $data);
    }
}
