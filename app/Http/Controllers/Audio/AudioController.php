<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Lib\SaveDataInDB;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnBgm;
use App\Models\DefaultBgm;
use App\Models\RoomBgm;
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
                $audio_name = $audio_file->getClientOriginalName();
                $audio_save_path = StoreFileInS3::userOwnFile($user_id, $audio_file);
                $audio_save_url= Storage::disk('s3')->url($audio_save_path);
                // サムネイル画像は、一次的にデフォルトのもの(♪マーク)で登録する
                $thumbnail_save_path = 'default/room/audio/thumbnail/8分音符アイコン 1.png';
                $thumbnail_save_url = 'https://hirosaka-testapp-room.s3-ap-northeast-1.amazonaws.com/default/room/audio/thumbnail/8%E5%88%86%E9%9F%B3%E7%AC%A6%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3+1.png';

                $fileDatas = array (
                        'owner_user_id' => $user_id,
                        'name' => $audio_name,
                        'audio_path' => $audio_save_path,
                        'audio_url' => $audio_save_url,
                        'thumbnail_path' => $thumbnail_save_path,
                        'thumbnail_url' => $thumbnail_save_url
                );
                SaveDataInDB::audio($fileDatas);

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
