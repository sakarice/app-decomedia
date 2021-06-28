<?php

namespace App\Http\Controllers\Ajax;

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
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;

use Storage;

class RoomController extends Controller
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
