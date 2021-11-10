<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Lib\MediaUtil;
use App\Models\User;
use App\Models\Media;

use Storage;

class MediaController extends Controller
{
    // destroy // ※!!自分のMediaのみ
    public static function destroy(Request $request){
        $user_id = Auth::user()->id;
        $selected_media_ids = $request->selectedMediaIds;
        // 選択されたMediaから、自分のMediaだけに絞る(=他ユーザのMediaを除外)
        $own_medias = Media::whereIn('id', $selected_media_ids)
                    ->where('user_id', $user_id)
                    ->get()
                    ->all();
        foreach($own_medias as $own_media){
            MediaUtil::deleteMediaDataFromDB($own_media->id);
        }
        return['message' => '選択した自分のmediaを削除しました。'];
    }

    
    
    // // media更新
    // public function updateMedia(Request $request){
    //     $media_id = $request->setting['id'];
    //     $returnMsg;

    //     DB::beginTransaction(); // 更新は、削除と作成のセットで実現
    //     try{
    //         MediaUtil::deleteMediaDataFromDB($media_id);
    //         MediaUtil::saveMediaDataInDB($request);
    //         DB::commit();
    //         $returnMsg = 'mediaを更新しました';
    //     } catch(\Exception $e){
    //         DB::rollback();
    //         $returnMsg = 'mediaの更新に失敗しました';
    //     }

    //     return['message' => $returnMsg];
    // }


}
