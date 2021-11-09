<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\PublicAudio;
use App\Models\PublicAudioThumbnail;
use App\Models\PublicAudioAudioThumbnail;

class PublicAudioController extends Controller
{
    // 1.index
    // Room作成・編集画面で使用。デフォルトBGMを取得
    public function index(){
        $default_audios = PublicAudio::get();
        $audios = array();
        foreach($default_audios as $index => $default_audio){
            $tmpAudios = array();
            $tmpAudios += array('name' => $default_audio->name);
            $tmpAudios += array('audio_url' => $default_audio->audio_url);
            $audio_thumbnail_id = PublicAudioAudioThumbnail::where('audio_id',$default_audio->id)->get()->first()->audio_thumbnail_id;
            if($audio_thumbnail_id){
                $tmpAudios += array('thumbnail_url' => PublicAudioThumbnail::find($audio_thumbnail_id)->img_url);
            } else {
                $tmpAudios += array('thumbnail_url' => "https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/public/img/audio_thumbnail/default/8%E5%88%86%E9%9F%B3%E7%AC%A6%E3%81%AE%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3%E7%B4%A0%E6%9D%90+2.png");
            }
            $audios[$index] = $tmpAudios;
        };
        return ['audios' => $audios];
    }

    // 2.create
    public function create(Request $request){}
    // 3.store
    public function store(Request $request){}
    // 4.show
    public function show($id){}
    // 5.edit
    public function edit($id){}
    // 6.update
    public function update($id){}
    // 7.destroy
    public function destroy($id){}
    
}
