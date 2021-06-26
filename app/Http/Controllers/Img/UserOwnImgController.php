<?php

namespace App\Http\Controllers\Img;

use App\Http\Controllers\Controller;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomImg;
use App\Models\UserOwnImg;

class UserOwnImgController extends Controller
{
    // 1.index
    // Room作成・編集画面で使用。ユーザのアップロードした画像を取得
    public function index(){
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
    
}
