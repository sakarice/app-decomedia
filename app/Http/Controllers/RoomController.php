<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\EditTrack;
use App\Lib\saveFile;
use App\Lib\RoomUtil;
use App\Http\Controllers\ImgController;
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

    // room作成メソッド
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

    // room閲覧メソッド
    public function show($room_id) {
        $room_data = RoomUtil::getRoomDatas($room_id);
        return view('rooms.show', $room_data);
    }

    // room編集メソッド
    public function edit($room_id){
        $room_data = RoomUtil::getRoomDatas($room_id);
        return view('rooms.edit', $room_data);
    }


    // Roomのプレビュー表示に必要な情報を取得(id,title,サムネ画像のurl)
    public static function getRoomPreviewInfos($rooms){
        $roomInfos = array();
        foreach($rooms as $room){
            $room_id = $room->id;

            if(RoomImg::where('room_id', $room_id)->exists()){
                $room_img_id = RoomImg::where('room_id', $room_id)->first()->img_id;
                $room_img_type = RoomImg::where('room_id', $room_id)->first()->img_type;
                $room_img;
                if($room_img_type == 1){
                $room_img = DefaultImg::where('id', $room_img_id)->first();
                } else if ($room_img_type == 2){
                $room_img = UserOwnImg::where('id', $room_img_id)->first();
                }
                $room_img_url = $room_img->img_url;
            } else if(RoomMovie::where('room_id', $room_id)->exists()) {
                // youtubeアイコンの画像URLをセット
                $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/3oLdT6SSOkEUW0ejXRWsLX177aXQVOd5vRa8Qtse.png";
            } else if(RoomBgm::where('room_id', $room_id)->exists()){
                // 音符アイコンの画像をセット
                $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/t6xoK6A2Wgy33J82wCzEvW12pnLqmeDkF4ASzqtO.jpg";
            } else {
                // empty画像をセット
                $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/tyOKqvszOb4LDP2egK6qTqWFzFiFnxlCurxaf98W.png"; 
            }

            $roomInfo = array(
                'id' => $room->id,
                'name' => $room->name,
                'preview_img_url' => $room_img_url,
            );
            \Log::info($roomInfo['id']);
            \Log::info($roomInfo['name']);
            \Log::info($roomInfo['preview_img_url']);

            $roomInfos[] = $roomInfo;
        }
        
        return($roomInfos);
    }



    public function getUserOwnImgs(){
        $owner_user_id = Auth::user()->id;
        $user_own_imgs = UserOwnImg::where('owner_user_id', $owner_user_id)->get();
        $img_file_urls = array();
        
        foreach($user_own_imgs as $user_own_img){
            $img_file_urls[] = $user_own_img->img_url;
        };

        return ['urls' => $img_file_urls];

    }

    public function getUserOwnAudioThumbnails(){
        $owner_user_id = Auth::user()->id;
        $user_own_audios = UserOwnBgm::where('owner_user_id', $owner_user_id)->get();
        $audio_thumbnail_file_urls = array();
        foreach($user_own_audios as $user_own_audio){
            $audio_thumbnail_file_urls[] = $user_own_audio->thumbnail_url;
        };

        return ['urls' => $audio_thumbnail_file_urls];
    }

    public function getDefaultAudioThumbnails(){
        $default_audios = DefaultBgm::get();
        $audio_thumbnail_file_urls = array();
        foreach($default_audios as $default_audio){
            $audio_thumbnail_file_urls[] = $default_audio->thumbnail_url;
        };

        return ['urls' => $audio_thumbnail_file_urls];
    }

    public function getUserOwnAudios(){
        $owner_user_id = Auth::user()->id;
        $user_own_audios = UserOwnBgm::where('owner_user_id', $owner_user_id)->get();
        $audios = array();
        foreach($user_own_audios as $index => $user_own_audio){
            $tmpAudios = array();
            $tmpAudios += array('audio_name' => $user_own_audio->name);
            $tmpAudios += array('audio_url' => $user_own_audio->audio_url);
            $tmpAudios += array('thumbnail_url' => $user_own_audio->thumbnail_url);
            $audios[$index] = $tmpAudios;
        };
        return ['audios' => $audios];
    }

    public function getDefaultAudios(){
        $default_audios = DefaultBgm::get();
        $audios = array();
        foreach($default_audios as $index => $default_audio){
            $tmpAudios = array();
            $tmpAudios += array('audio_name' => $default_audio->name);
            $tmpAudios += array('audio_url' => $default_audio->audio_url);
            $tmpAudios += array('thumbnail_url' => $default_audio->thumbnail_url);
            $audios[$index] = $tmpAudios;
        };
        return ['audios' => $audios];
    }

    public function getDefaultImgs(){
        $owner_user_id = Auth::user()->id;
        $default_imgs = DefaultImg::get();
        $img_file_urls = array();
        
        foreach($default_imgs as $default_img){
            $img_file_urls[] = $default_img->img_url;
        };

        return ['urls' => $img_file_urls];
    }



    public function saveImgFile(Request $request) {
        $user_id = Auth::user()->id;
        $imgfile = $request->file('img');
        $imgfile_name = $imgfile->getClientOriginalName();
        $imgfile_save_path = saveFile::saveFileInS3($user_id, $imgfile);
        $imgfile_save_url = Storage::disk('s3')->url($imgfile_save_path);

        $fileDatas = array (
            'owner_user_id' => $user_id,
            'name' => $imgfile_name,
            'img_path' => $imgfile_save_path,
            'img_url' => $imgfile_save_url
        );
        saveFile::saveImgDataInDB($fileDatas);

        return ['url' => $imgfile_save_url];
    }

    public function saveAudioFile(Request $request) {
        $user_id = Auth::user()->id;
        $audio_file = $request->file('audio');
        $audio_name = $audio_file->getClientOriginalName();
        $audio_save_path = saveFile::saveFileInS3($user_id, $audio_file);
        $audio_save_url= Storage::disk('s3')->url($audio_save_path);
        // サムネイル画像は、一次的にデフォルトのもの(♪マーク)で登録する
        $thumbnail_save_path = 'default/room/audio/thumbnail/8分音符アイコン 1.png';
        $thumbnail_save_url = 'https://hirosaka-testapp-room.s3-ap-northeast-1.amazonaws.com/default/room/audio/thumbnail/8%E5%88%86%E9%9F%B3%E7%AC%A6%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3+1.png';

        $fileDatas = array (
            'owner_user_id' => $user_id,
            'name' => $audio_name,
            'path' => $audio_save_path,
            'url' => $audio_save_url,
            'thumbnail_path' => $thumbnail_save_path,
            'thumbnail_url' => $thumbnail_save_url
        );
        saveFile::saveAudioDataInDB($fileDatas);

        $audios = array(
            'audio_name' => $audio_name,
            'audio_url' => $audio_save_url,
            'thumnbail_url' => $thumbnail_save_url
        );

        return ['audios' => $audios];
    }

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
        saveFile::saveImgDataInDB($fileDatas);

        return ['url' => $imgfile_save_url];
    }



    public function deleteImgFile(Request $request){
        $owner_user_id = Auth::user()->id;
        $del_imgfile_url = $request->imgUrl;
        // S3からファイルを削除
        Storage::disk('s3')->delete($del_imgfile_url);
        // DBからレコード削除
        UserOwnImg::where('owner_user_id', $owner_user_id)
                    ->where('img_url', $del_imgfile_url)
                    ->first()
                    ->delete();

        return ['削除完了しました'];
    }

    public function deleteAudio(Request $request){
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
