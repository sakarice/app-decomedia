<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\RoomImgController;
use App\Http\Controllers\RoomAudioController;
use App\Http\Controllers\RoomMovieController;
use App\Http\Controllers\RoomSettingController;
use App\Lib\StoreFileInS3;
use App\Lib\RoomUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\UserOwnBgm;
use App\Models\DefaultImg;
use App\Models\DefaultBgm;
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;
use Storage;

class RoomController extends Controller
{
    // 1. index
    public function index() {}

    // 2. create
    public function create() {
        return view('rooms.create');
    }

    // 3. store
    public function store(Request $request){
        $returnMsg;
        DB::beginTransaction();
        try{
            RoomUtil::saveRoomDataInDB($request);
            DB::commit();
            $returnMsg = 'roomを保存しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの保存に失敗しました';
        }

        return ['message' => $returnMsg];        
    }

    // 4. show
    public function show($room_id) {
        $room_data = RoomUtil::getRoomDatas($room_id);
        $data = $room_data + array('isLogin' => Auth::check());
        return view('rooms.show', $data);
    }

    // 5. edit
    public function edit($room_id){
        $room_data = RoomUtil::getRoomDatas($room_id);
        return view('rooms.edit', $room_data);
    }

    // 6. update
    public function update(Request $request){
        $room_id = $request->setting['id'];
        $returnMsg;

        DB::beginTransaction(); // 更新は、削除と作成のセットで実現
        try{
            // $this->destroy($room_id);
            RoomUtil::deleteRoomDataFromDB($room_id);
            RoomUtil::saveRoomDataInDB($request);
            DB::commit();
            $returnMsg = 'roomを更新しました';
        } catch(\Exception $e){
            DB::rollback();
            $returnMsg = 'roomの更新に失敗しました';
        }

        return['message' => $returnMsg];
    }

    // 7. destroy
    public function destroy(Request $request){
        // $user_id = Auth::user()->id;
        $room_id = $request->room_id;
        $returnMsg = RoomUtil::deleteRoomDataFromDB($room_id);
        return['message' => $returnMsg];
    }

}
