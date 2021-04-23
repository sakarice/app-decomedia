<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\EditTrack;
use App\Lib\saveFile;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\UserOwnBgm;

use Storage;

class CreateRoom2Controller extends Controller
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


    public function getImgFileUrls(){
        $owner_user_id = Auth::user()->id;
        $user_own_imgs = UserOwnImg::where('owner_user_id', $owner_user_id)->get();
        $img_file_urls = array();
        
        foreach($user_own_imgs as $user_own_img){
            $img_file_urls[] = $user_own_img->img_url;
        };

        return ['urls' => $img_file_urls];

    }


    public function saveImgFile(Request $request) {
        $user_id = Auth::user()->id;
        $imgfile = $request->file('img');
        $imgfile_name = $imgfile->getClientOriginalName();
        $imgfile_save_path = saveFile::saveFileInS3($user_id, $imgfile);
        $imgfile_save_url = Storage::disk('s3')->url($imgfile_save_path);

        $fileData = array (
            'owner_user_id' => $user_id,
            'name' => $imgfile_name,
            'img_path' => $imgfile_save_path,
            'img_url' => $imgfile_save_url
        );
        saveFile::saveUserOwnImgDataInDB($fileData);

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
