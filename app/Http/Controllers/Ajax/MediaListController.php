<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Lib\MediaListUtil;
use App\Lib\MediaMediaListUtil;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\PublicImg;
use App\Models\Media;
use App\Models\Medialist;
use App\Models\MediaMedialist;
use App\Models\MediaImg;

use Storage;

class MediaListController extends Controller
{

    // Mediaリストのプレビュー情報を取得
    public static function getMediaListPreviewInfos($num){
        $user_id = Auth::user()->id;
        $media_lists = Medialist::limit($num)->where('user_id', $user_id)->get();
        $mediaListPreviewInfos = array();
        foreach($media_lists as $index => $media_list){
            $media_list_id = $media_list->id;
            $mediaListPreviewInfos[] = MediaListUtil::getMediaListPreviewInfo($media_list_id);
        }
        return ['mediaListPreviewInfos' => $mediaListPreviewInfos];
    }

    // マイページからMediaを選択して手早くMediaリストを作成する処理
    // Mediaリスト名などの細かい設定は省略
    public static function quickStore(Request $request){
        $user_id = Auth::user()->id;
        $selectedMediaIds = $request->selectedMediaIds;


        // Mediaリストを保存 細かい設定はなにも無し
        $medialist = new MediaList();
        $medialist->user_id = $user_id;
        $medialist->name = 'Mediaリスト'.(MediaList::max('id')+1);
        $firstMediaImgType = MediaImg::where('media_id', $selectedMediaIds[0])->first()->img_type;
        $firstMediaImgId = MediaImg::where('media_id', $selectedMediaIds[0])->first()->img_id;

        $firstMediaImgUrl;
        if($firstMediaImgType == 1){
            $firstMediaImgUrl = PublicImg::find($firstMediaImgId)->img_url;
        } else if ($firstMediaImgType == 2){
            $firstMediaImgUrl = UserOwnImg::find($firstMediaImgId)->img_url;
        }

        $medialist->thumbnail_img_url = $firstMediaImgUrl;
        // $medialist->description = NULL;
        $medialist->save();

        // Media - Medialistテーブル(中間テーブル)にも保存
        $medialistId = MediaList::latest()->first()->id;
        foreach($selectedMediaIds as $index => $selectedMediaId){
            $mediaMedialist = new MediaMedialist();
            $mediaMedialist->medialist_id = $medialistId;
            $mediaMedialist->media_id = $selectedMediaId;
            $mediaMedialist->media_order_seq = $index + 1;
            $mediaMedialist->save();
        }
        DB::beginTransaction();
        try{
            DB::commit();
        } catch(\Exception $e){
            DB::rollback();
        }

        return ['message' => 'succeess!!!!!'];
    }


    public static function destroy(Request $request){
        $media_list_id = $request->mediaList_id;
        $returnMsg;
        DB::beginTransaction();
        try {
            MediaListUtil::deleteMediaListDataFromDB($media_list_id);
            MediaMediaListUtil::deleteMediaMediaListDataFromDB($media_list_id);
            DB::commit();
            $returnMsg = 'mediaリストを削除しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'mediaリストの削除に失敗しました';
        }
        return ['message' => $returnMsg];
    }



}
