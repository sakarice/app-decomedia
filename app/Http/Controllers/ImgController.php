<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Lib\ImgUtil;
use App\Models\User;
use App\Models\Room;
use Storage;

class ImgController extends Controller
{
    // 1.index
    public function index(){}
    // 2.create
    public function create(Request $request){}

    // 3.store
    // Room作成・編集画面でユーザがアップロードした画像ファイルを保存する。
    public function store(Request $request){
        $user_id = Auth::user()->id;
        $imgfile = $request->file('img');
        $imgfile_name = $imgfile->getClientOriginalName();
        $imgfile_save_path = StoreFileInS3::userOwnMediaFile($user_id, $imgfile);
        $imgfile_save_url = Storage::disk('s3')->url($imgfile_save_path);

        $fileDatas = array (
            'owner_user_id' => $user_id,
            'name' => $imgfile_name,
            'path' => $imgfile_save_path,
            'url' => $imgfile_save_url
        );
        $img_id = ImgUtil::saveImgData($fileDatas);
        $img_file_info = array('id' => $img_id);
        $img_file_info += array('url'=> $imgfile_save_url);

        return [
            'img_file_info' => $img_file_info 
        ];        
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
