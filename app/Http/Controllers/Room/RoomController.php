<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\EditTrack;
use App\Lib\StoreFileInS3;
use App\Lib\RoomUtil;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RoomSettingController;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\UserOwnBgm;
use App\Models\DefaultImg;
use App\Models\DefaultBgm;
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;
use Storage;

class RoomController extends Controller
{
    // 1. index
    public function index() {
        if(Auth::check()){
            $checked = "ユーザー：".Auth::user()->name."は認証済みです";
            $data = [
                'msg' => $checked,
            ];
            return view('rooms.create', $data);
        } else {
            return view('auth.login');
            // $checked = "ユーザー：".Auth::user()->name."は認証されていません";
        }
    }

    // 2. create
    public function create() {
        if(Auth::check()){
            $checked = "ユーザー：".Auth::user()->name."は認証済みです";
            $data = [
                'msg' => $checked,
            ];
            return view('rooms.create', $data);
        } else {
            return view('auth.login');
        }
    }

    // 3. store
    public function store(){}

    // 4. show
    public function show($room_id) {
        $room_data = RoomUtil::getRoomDatas($room_id);
        return view('rooms.show', $room_data);
    }

    // 5. edit
    public function edit($room_id){
        $room_data = RoomUtil::getRoomDatas($room_id);
        return view('rooms.edit', $room_data);
    }

    // 6. update
    public function update($room_id){}

    // 7. destroy
    public function destroy($room_id){}




    public function saveDBTest() {
        $user_id = NULL;
        $imgfile_name = 'rail.jpg';
        $imgfile_save_path = 'img/room/rail.jpg';
        $imgfile_save_url = 'https://hirosaka-testapp-room.s3-ap-northeast-1.amazonaws.com/img/room/rail.jpg';

        $fileDatas = array (
            'owner_user_id' => $user_id,
            'name' => $imgfile_name,
            'img_path' => $imgfile_save_path,
            'img_url' => $imgfile_save_url
        );
        saveDataInDB::img($fileDatas);

        return ['url' => $imgfile_save_url];
    }


    public function createTrack(Request $request) {

        $imgfile = $request->file('img');
        $audiofile = $request->file('audio');

        // ファイルのMIMEタイプを確認
        // $mimeFront1 = EditTrack::judgeMimetypeFront($imgfile);
        // $mimeFront2 = EditTrack::judgeMimetypeFront($audiofile);

        // DBへ登録するトラック情報を取得
        $user_id = Auth::user()->id;
        $title = $request->input('sound-title-in-hidden');
        $img_path = EditTrack::saveTrackInS3($imgfile);
        $sound_path = EditTrack::saveTrackInS3($audiofile);
        $img_url = Storage::disk('s3')->url($img_path);
        $sound_url = Storage::disk('s3')->url($sound_path);

        $trackInfo = array(
            'user_id' => $user_id,
            'title' => $title,
            'img_path' => $img_path,
            'sound_path' => $sound_path,
            'img_url' => $img_url,
            'sound_url' => $sound_url
        );

        EditTrack::saveTrackInfoInDB($trackInfo);

        return view('edittrack.create', ['msg'=> Storage::disk('s3')->url(Track::latest()->first()->img_path)]);
    }

}
