<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\EditTrack;
use App\Lib\StoreFileInS3;
use App\Lib\saveDataInDB;
use App\Lib\RoomUtil;
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


    // room作成
    public function createRoom(Request $request){
        // ★デバッグ用ログ出力 DBに保存する情報が取得できているか確認
        // $user_id = Auth::user()->id;
        // \Log::info('ユーザID：'.$user_id);
        $returnMsg;
        
        DB::beginTransaction();
        try{
            RoomUtil::saveRoomDataInDB($request);
            DB::commit();
            $returnMsg = 'roomを保存しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの保存に失敗しました';
        }

        return ['message' => $returnMsg];
    }


    // room削除
    public function deleteRoom (Request $request){
        $room_id = $request->room_id;
        // $user_id = Auth::user()->id;
        $returnMsg;
        
        DB::beginTransaction();
        try{
            RoomUtil::deleteRoomDataFromDB($room_id);
            DB::commit();
            $returnMsg = 'roomを削除しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの削除に失敗しました';
        }

        return['message' => $returnMsg];
    }


    // room更新
    public function updateRoom(Request $request){
        $room_id = $request->setting['id'];
        $returnMsg;

        DB::beginTransaction(); // 更新は、削除と作成のセットで実現
        try{
            RoomUtil::deleteRoomDataFromDB($room_id);
            RoomUtil::saveRoomDataInDB($request);
            DB::commit();
            $returnMsg = 'roomを更新しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの更新に失敗しました';
        }

        return['message' => $returnMsg];
    }


}
