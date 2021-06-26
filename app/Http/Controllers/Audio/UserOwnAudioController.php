<?php

namespace App\Http\Controllers\Audio;

use App\Http\Controllers\Controller;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomBgm;
use App\Models\UserOwnBgm;

class UserOwnAudioController extends Controller
{
    // 1.index
    // Room作成・編集画面で使用。デフォルトBGMを取得
    public function index(){
        $owner_user_id = Auth::user()->id;
        $user_own_audios = UserOwnBgm::where('owner_user_id', $owner_user_id)->get();
        $audios = array();
        foreach($user_own_audios as $index => $user_own_audio){
            $tmpAudios = array();
            $tmpAudios += array('name' => $user_own_audio->name);
            $tmpAudios += array('url' => $user_own_audio->audio_url);
            $tmpAudios += array('thumbnail_url' => $user_own_audio->thumbnail_url);
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
    public function destroy(Request $request){
        $owner_user_id = Auth::user()->id;
        $del_audio_url = $request->audioUrl;
        // S3からファイルを削除
        Storage::disk('s3')->delete($del_audio_url);
        // DBからレコード削除
        \Log::info($del_audio_url);
        UserOwnBgm::where('owner_user_id', $owner_user_id)
                    ->where('audio_url', $del_audio_url)
                    ->first()
                    ->delete();

        return ['削除完了しました'];
    }
    
}
