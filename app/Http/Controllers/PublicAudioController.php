<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\PublicAudio;
use App\Models\PublicAudioThumbnail;
use App\Models\PublicAudioAudioThumbnail;
use App\Models\PublicAudioAudioCategory;
use App\Models\AudioCategory;

class PublicAudioController extends Controller
{
    // 1.index
    // Media作成・編集画面で使用。デフォルトBGMを取得
    public function index(){
        $public_audios = PublicAudio::get();
        $audios = array();
        // $count = 0;
        foreach($public_audios as $index => $public_audio){
            $tmpAudios = array();
            $tmpAudios += array('name' => $public_audio->name);
            $tmpAudios += array('audio_id' => $public_audio->id);
            $tmpAudios += array('audio_url' => $public_audio->audio_url);
            $audio_thumbnail_id = PublicAudioAudioThumbnail::where('audio_id',$public_audio->id)->get()->first()->audio_thumbnail_id;
            if($audio_thumbnail_id){
                $tmpAudios += array('thumbnail_url' => config('aws_cloud_front.distribution')."/".PublicAudioThumbnail::find($audio_thumbnail_id)->img_path);
                \Log::info($tmpAudios['thumbnail_url']);
            } else {
                $tmpAudios += array('thumbnail_url' => "https://".config('app.aws_bucket').".s3.".config('app.aws_default_region').".amazonaws.com/public/img/audio_thumbnail/default/8%E5%88%86%E9%9F%B3%E7%AC%A6%E3%81%AE%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3%E7%B4%A0%E6%9D%90+2.png");
            }
            if(PublicAudioAudioCategory::where('audio_id',$public_audio->id)->exists()){
                $category_id = PublicAudioAudioCategory::where('audio_id',$public_audio->id)->first()->category_id;
                $category = AudioCategory::find($category_id)->category;
            } else {
                $category = "";
            }
            $tmpAudios += array('category' => $category);
            $audios[$index] = $tmpAudios;
            // $count = $count+1;
            // \Log::info($count);
            // if($count >= 3){ break;}
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
