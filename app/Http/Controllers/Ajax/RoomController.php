<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\EditTrack;
use App\Lib\saveFile;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\UserOwnBgm;
use App\Models\DefaultImg;
use App\Models\DefaultBgm;
use App\Models\Room;
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;

use Storage;

class RoomController extends Controller
{
    //
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


    // public function getUserOwnImgs(){
    //     $owner_user_id = Auth::user()->id;
    //     $user_own_imgs = UserOwnImg::where('owner_user_id', $owner_user_id)->get();
    //     $img_file_urls = array();
        
    //     foreach($user_own_imgs as $user_own_img){
    //         $img_file_urls[] = $user_own_img->img_url;
    //     };

    //     return ['urls' => $img_file_urls];
    // }

    public function getUserOwnImgs(){
        $owner_user_id = Auth::user()->id;
        $user_own_imgs = UserOwnImg::where('owner_user_id', $owner_user_id)->get();
        $img_file_datas = array();
        
        foreach($user_own_imgs as $index => $user_own_img){
            $tmp_img_file_datas = array();
            $tmp_img_file_datas += array('id' => $user_own_img->id);
            $tmp_img_file_datas += array('url'=> $user_own_img->img_url);
            $img_file_datas[$index] = $tmp_img_file_datas;
        };

        return ['file_datas' => $img_file_datas];
    }

    public function getDefaultImgs(){
        $owner_user_id = Auth::user()->id;
        $default_imgs = DefaultImg::get();
        $img_file_datas = array();
        
        foreach($default_imgs as $index => $default_img){
            $tmp_img_file_datas = array();
            $tmp_img_file_datas += array('id' => $default_img->id);
            $tmp_img_file_datas += array('url' => $default_img->img_url);
            $img_file_datas[$index] = $tmp_img_file_datas;
        };

        return ['file_datas' => $img_file_datas];
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
            // $tmpAudios += array('id' => $user_own_audio->id);
            $tmpAudios += array('name' => $user_own_audio->name);
            $tmpAudios += array('url' => $user_own_audio->audio_url);
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
            // $tmpAudios += array('id' => $default_audio->id);
            $tmpAudios += array('name' => $default_audio->name);
            $tmpAudios += array('url' => $default_audio->audio_url);
            $tmpAudios += array('thumbnail_url' => $default_audio->thumbnail_url);
            $audios[$index] = $tmpAudios;
        };
        return ['audios' => $audios];
    }


    public function saveImgFile(Request $request) {
        $user_id = Auth::user()->id;
        $imgfile = $request->file('img');
        $imgfile_name = $imgfile->getClientOriginalName();
        $imgfile_save_path = saveFile::saveFileInS3($user_id, $imgfile);
        $imgfile_save_url = Storage::disk('s3')->url($imgfile_save_path);
        \Log::info($imgfile_save_url);

        $fileDatas = array (
            'owner_user_id' => $user_id,
            'name' => $imgfile_name,
            'img_path' => $imgfile_save_path,
            'img_url' => $imgfile_save_url
        );
        $id = saveFile::saveImgDataInDB($fileDatas);
        // $img_file_info = array();
        $img_file_info = array('id' => $id);
        $img_file_info += array('url'=> $imgfile_save_url);


        return [
            'img_file_info' => $img_file_info 
        ];
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
            // 'id' => $id,
            'name' => $audio_name,
            'url' => $audio_save_url,
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
        // UserOwnImg::where('owner_user_id', $owner_user_id)
        //             ->where('img_url', $del_imgfile_url)
        //             ->first()
        //             ->delete();

        $del_imgfile_id = $request->imgId;
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

    public function createRoom(Request $request){
        
        // ★デバッグ用ログ出力 DBに保存する情報が取得できているか確認
        // room
        $user_id = Auth::user()->id;
        \Log::info('ユーザID：'.$user_id);
        $returnMsg;
        
        DB::beginTransaction();
        try{
            // room保存
            $room = new Room();
            $room->user_id = $user_id;
            if(isset($request->setting['name'])){
                $room->name = $request->setting['name'];
            }else {
                $room->name = 'room';
            }
            $room->save();

    
            // 保存したroomのidを取得
            $room_id = Room::latest()->first()->id;
    
            // room画像
            $roomImg = new RoomImg();
            $roomImg->room_id = $room_id;
            $room_img_type;
            if($request->img['type'] == 'default-img'){
                $room_img_type = 1; // default
            } else if($request->img['type'] == 'user-own-img'){
                $room_img_type = 2; // userOwn
            }
            $roomImg->img_type = $room_img_type;
            $roomImg->img_id = $request->img['id'];
            // $roomImg->img_url = $request->img['url'];
            $roomImg->width = $request->img['width'];
            $roomImg->height = $request->img['height'];
            $roomImg->owner_user_id = $user_id;
            $roomImg->img_layer = $request->img['layer'];
            $roomImg->save();


    
            // room動画
            $roomMovie = new RoomMovie();
            $roomMovie->user_id = $user_id;
            $roomMovie->room_id = $room_id;
            $roomMovie->video_id = $request->movie['videoId'];
            $roomMovie->width = $request->movie['width'];
            $roomMovie->height = $request->movie['height'];
            $roomMovie->isLoop = $request->movie['isLoop'];
            $roomMovie->movie_layer = $request->movie['layer'];
            $roomMovie->save();
    

            // room音楽
            $roomAudios = $request->audios;
            foreach($roomAudios as $index => $roomAudio){
                $roomBgm = new RoomBgm();
                $audio_id;
                if($roomAudio['type'] == 1){
                    $audio_id = DefaultBgm::where('audio_url', $roomAudio['url'])
                                            ->first()
                                            ->id;
                } else if ($roomAudio['type'] == 2){
                    $audio_id = UserOwnBgm::where('audio_url', $roomAudio['url'])
                                            ->first()
                                            ->id;
                }
    
                $roomBgm->room_id = $room_id;
                $roomBgm->audio_type = $roomAudio['type'];
                $roomBgm->audio_id = $audio_id;
                $roomBgm->order_seq = $index + 1;
                $roomBgm->volume = $roomAudio['volume'];
                $roomBgm->isLoop = $roomAudio['isLoop'];
                if($roomAudio['type'] == 2){ //2:ユーザのアップロードした音楽
                    $roomBgm->owner_user_id = $user_id;
                } // 2以外はdefaultのオーディオ(=ユーザのものでない）ので、NULLで良い
                $roomBgm->save();

            }
    
            // room設定
            $roomSetting = new RoomSetting();
            $roomSetting->room_id = $room_id;
            $roomSetting->is_show_img = $request->setting['isShowImg'];
            $roomSetting->is_show_movie = $request->setting['isShowMovie'];
            $roomSetting->max_audio_num = $request->setting['maxAudioNum'];
            // $roomSetting->background_type = $request->setting['roomBackgroundType'];
            $roomSetting->background_color = $request->setting['roomBackgroundColor'];
            $roomSetting->save();

            DB::commit();
            $returnMsg = 'roomを保存しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの保存に失敗しました';
        }

        return ['message' => $returnMsg];
    }


    public function deleteRoom (Request $request){
        $room_id = $request->room_id;
        $user_id = Auth::user()->id;
        $returnMsg;
        
        DB::beginTransaction();
        try{
            // Room
            Room::where('id', $room_id)
                ->where('user_id', $user_id)
                ->first()
                ->delete();
    
            // Room画像
            RoomImg::where('room_id', $room_id)->first()->delete();
    
            // Room音楽
            RoomBgm::where('room_id', $room_id)->delete();
    
            // Room動画
            RoomMovie::where('room_id', $room_id)->first()->delete();
    
            // Room設定
            RoomSetting::where('room_id', $room_id)->first()->delete();

            DB::commit();
            $returnMsg = 'roomを削除しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの削除に失敗しました';

        }

        return['message' => $returnMsg];

    }


}
