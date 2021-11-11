<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Lib\Common\StringProcessing;
use App\Lib\AudioUtil;
use App\Models\User;
use App\Models\UserOwnAudioAudioThumbnail;
use Storage;

class AudioController extends Controller
{
        // 1.index
        public function index(){}
        // 2.create
        public function create(Request $request){}
        // 3.store
        public function store(Request $request){
                $user_id = Auth::user()->id;
                $audio_file = $request->file('audio');
                $audio_name = StringProcessing::getFilenameExceptExt($audio_file->getClientOriginalName());
                $audio_save_path = StoreFileInS3::userOwnMediaFile($user_id, $audio_file);
                $audio_save_url= Storage::disk('s3')->url($audio_save_path);
                // サムネイル画像は、一次的にデフォルトのもの(♪マーク)で登録する
                $thumbnail_save_path = 'public/img/audio_thumbnail/default/8分音符のアイコン素材 2.png';
                $thumbnail_save_url = "https://".config('app.aws_bucket').".s3.".config('app.aws_default_region').".amazonaws.com/public/img/audio_thumbnail/default/8%E5%88%86%E9%9F%B3%E7%AC%A6%E3%81%AE%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3%E7%B4%A0%E6%9D%90+2.png";

                $fileDatas = array (
                        'owner_user_id' => $user_id,
                        'name' => $audio_name,
                        'path' => $audio_save_path,
                        'url' => $audio_save_url,
                        'thumbnail_path' => $thumbnail_save_path,
                        'thumbnail_url' => $thumbnail_save_url
                );
                $audio_id = AudioUtil::saveAudioData($fileDatas);
                
                // オーディオとサムネイルの中間テーブルにもデータを保存
                $public_audio_audio_thumbnail = new UserOwnAudioAudioThumbnail;
                $public_audio_audio_thumbnail->audio_id = $audio_id;
                $public_audio_audio_thumbnail->save();

                $audios = array(
                        // 'id' => $id,
                        'name' => $audio_name,
                        'audio_url' => $audio_save_url,
                        'thumnbail_url' => $thumbnail_save_url
                );

                return ['audios' => $audios];                
        }

        // 4.show
        public function show($id){}
        // 5.edit
        public function edit($id){}
        // 6.update
        public function update($id){}
        // 7.destroy
        public function destroy($id){}
    


}
