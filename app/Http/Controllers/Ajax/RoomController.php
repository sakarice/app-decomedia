<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Lib\RoomUtil;
use App\Models\User;
use App\Models\Room;

use Storage;

class RoomController extends Controller
{
    // destroy // ※!!自分のRoomのみ
    public static function destroy(Request $request){
        $user_id = Auth::user()->id;
        $selected_room_ids = $request->selectedRoomIds;
        // 選択されたRoomから、自分のRoomだけに絞る(=他ユーザのRoomを除外)
        $own_rooms = Room::whereIn('id', $selected_room_ids)
                    ->where('user_id', $user_id)
                    ->get()
                    ->all();
        foreach($own_rooms as $own_room){
            RoomUtil::deleteRoomDataFromDB($own_room->id);
        }
        return['message' => '選択した自分のroomを削除しました。'];
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
