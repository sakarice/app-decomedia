<?php

namespace App\Http\Controllers\Ajax\RoomList;

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
use App\Models\Roomlist;
use App\Models\RoomRoomlist;
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;

use Storage;

class RoomListController extends Controller
{
    //
    public function index() {
        if(Auth::check()){
            $checked = "ユーザー：".Auth::user()->name."は認証済みです";
            $data = [
                'msg' => $checked,
            ];
        }
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
            $firstRoomImgUrl = DefaultImg::find($firstRoomImgId)->img_url;
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


    // 入ったroomが自分の作成したroomか判別する
    public static function judgeIsMyRoom($room_id){
        $enter_user_id = Auth::user()->id;
        $room_owner_user_id = Room::find($room_id)->user_id;
        $isMyRoom;

        if($enter_user_id == $room_owner_user_id){
        $isMyRoom = true;
        } else {
        $isMyRoom = false;
        }

        return ['isMyRoom' => $isMyRoom];
    }

    
    // // room更新
    // public function updateRoom(Request $request){
    //     $room_id = $request->setting['id'];
    //     $returnMsg;

    //     DB::beginTransaction(); // 更新は、削除と作成のセットで実現
    //     try{
    //         RoomUtil::deleteRoomDataFromDB($room_id);
    //         RoomUtil::saveRoomDataInDB($request);
    //         DB::commit();
    //         $returnMsg = 'roomを更新しました';
    //     } catch(\Exception $e){
    //         DB::rollback();
    //         $returnMsg = 'roomの更新に失敗しました';
    //     }

    //     return['message' => $returnMsg];
    // }


}
