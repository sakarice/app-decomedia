<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\AudioController;
use App\Lib\StoreFileInS3;
use App\Lib\MediaUtil;
use App\Models\User;
use App\Models\Media;
use Storage;

class MediaController extends Controller
{
    // 1. index
    public function index() {}

    // 2. create
    public function create() {
        return view('medias.create');
    }

    // 3. store
    public function store(Request $request){
        $returnMsg;
        // DB::beginTransaction();
        try{
            MediaUtil::saveMediaDataInDB($request);
            // DB::commit();
            $returnMsg = 'mediaを保存しました';
        } catch(\Exception $e){
            // DB::rollback();
            $returnMsg = 'mediaの保存に失敗しました';
        }

        return ['message' => $returnMsg];        
    }

    // 4. show
    public function show($media_id) {
        $media_data = MediaUtil::getMediaDatas($media_id);
        $data = $media_data + array('isLogin' => Auth::check());
        return view('medias.show', $data);
    }

    // 5. edit
    public function edit($media_id){
        $media_data = MediaUtil::getMediaDatas($media_id);
        return view('medias.edit', $media_data);
    }

    // 6. update
    public function update(Request $request){
        $media_id = $request->setting['id'];
        $returnMsg;

        DB::beginTransaction(); // 更新は、削除と作成のセットで実現
        try{
            // $this->destroy($media_id);
            // MediaUtil::deleteMediaDataFromDB($media_id);
            // MediaUtil::saveMediaDataInDB($request);
            MediaUtil::updateMediaData($media_id, $request);
            DB::commit();
            $returnMsg = 'mediaを更新しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'mediaの更新に失敗しました';
        }

        return['message' => $returnMsg];
    }

    // 7. destroy
    public function destroy(Request $request){
        // $user_id = Auth::user()->id;
        $media_id = $request->media_id;
        $returnMsg = MediaUtil::deleteMediaDataFromDB($media_id);
        return['message' => $returnMsg];
    }

}
