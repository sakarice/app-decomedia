<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Models\User;
use App\Models\Media;
use App\Models\PublicImg;
use App\Models\PublicImgImgCategory;
use App\Models\ImgCategory;

class PublicImgController extends Controller
{
    // 1.index
    // Media作成・編集画面で使用。デフォルト画像を取得
    public function index(){
        $owner_user_id = Auth::user()->id;
        $public_imgs = PublicImg::get();
        $img_file_datas = array();
        
        foreach($public_imgs as $index => $public_img){
            $tmp_img_file_datas = array();
            $tmp_img_file_datas += array('id' => $public_img->id);
            $tmp_img_file_datas += array('url' => $public_img->img_url);
            if(PublicImgImgCategory::where('img_id', $public_img->id)->exists()){
                $img_category_id = PublicImgImgCategory::where('img_id', $public_img->id)->first()->category_id;
                $img_category = ImgCategory::find($img_category_id)->category;
            } else {
                $img_category = "";
            }
            $tmp_img_file_datas += array('category' => $img_category);
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
    public function destroy($id){}
    
}
