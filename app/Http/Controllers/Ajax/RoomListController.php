<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Lib\RoomListUtil;
use App\Lib\RoomRoomListUtil;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\PublicImg;
use App\Models\Room;
use App\Models\Roomlist;
use App\Models\RoomRoomlist;
use App\Models\RoomImg;

use Storage;

class RoomListController extends Controller
{

    // Roomリストのプレビュー情報を取得
    public static function getRoomListPreviewInfos($num){
        $user_id = Auth::user()->id;
        $room_lists = Roomlist::limit($num)->where('user_id', $user_id)->get();
        $roomListPreviewInfos = array();
        foreach($room_lists as $index => $room_list){
            $room_list_id = $room_list->id;
            $roomListPreviewInfos[] = RoomListUtil::getRoomListPreviewInfo($room_list_id);
        }
        return ['roomListPreviewInfos' => $roomListPreviewInfos];
    }

    // マイページからRoomを選択して手早くRoomリストを作成する処理
    // Roomリスト名などの細かい設定は省略
    public static function quickStore(Request $request){
        $user_id = Auth::user()->id;
        $selectedRoomIds = $request->selectedRoomIds;


        // Roomリストを保存 細かい設定はなにも無し
        $roomlist = new RoomList();
        $roomlist->user_id = $user_id;
        $roomlist->name = 'Roomリスト'.(RoomList::max('id')+1);
        $firstRoomImgType = RoomImg::where('room_id', $selectedRoomIds[0])->first()->img_type;
        $firstRoomImgId = RoomImg::where('room_id', $selectedRoomIds[0])->first()->img_id;

        $firstRoomImgUrl;
        if($firstRoomImgType == 1){
            $firstRoomImgUrl = PublicImg::find($firstRoomImgId)->img_url;
        } else if ($firstRoomImgType == 2){
            $firstRoomImgUrl = UserOwnImg::find($firstRoomImgId)->img_url;
        }

        $roomlist->thumbnail_img_url = $firstRoomImgUrl;
        // $roomlist->description = NULL;
        $roomlist->save();

        // Room - Roomlistテーブル(中間テーブル)にも保存
        $roomlistId = RoomList::latest()->first()->id;
        foreach($selectedRoomIds as $index => $selectedRoomId){
            $roomRoomlist = new RoomRoomlist();
            $roomRoomlist->roomlist_id = $roomlistId;
            $roomRoomlist->room_id = $selectedRoomId;
            $roomRoomlist->room_order_seq = $index + 1;
            $roomRoomlist->save();
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
        $room_list_id = $request->roomList_id;
        $returnMsg;
        DB::beginTransaction();
        try {
            RoomListUtil::deleteRoomListDataFromDB($room_list_id);
            RoomRoomListUtil::deleteRoomRoomListDataFromDB($room_list_id);
            DB::commit();
            $returnMsg = 'roomリストを削除しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomリストの削除に失敗しました';
        }
        return ['message' => $returnMsg];
    }



}
