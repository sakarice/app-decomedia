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
use App\Models\DefaultBgm;

class DefaultAudioController extends Controller
{
    // 1.index
    // Room作成・編集画面で使用。デフォルトBGMを取得
    public function index(){
        $default_audios = DefaultBgm::get();
        $audios = array();
        foreach($default_audios as $index => $default_audio){
            $tmpAudios = array();
            $tmpAudios += array('name' => $default_audio->name);
            $tmpAudios += array('url' => $default_audio->audio_url);
            $tmpAudios += array('thumbnail_url' => $default_audio->thumbnail_url);
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
