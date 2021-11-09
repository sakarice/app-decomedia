<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Lib\ImgUtil;
use App\Lib\MediaImgUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomImg;
use Storage;

class RoomImgController extends Controller
{
    // 1.index
    public function index(){}
    // 2.create
    public function create(Request $request){}
    // 3.store
    public static function store($room_id, $request){
        MediaImgUtil::saveRoomImgData($room_id, $request);
    }

    // 4.show   // Room画像情報の連想配列を返す
    public static function show($room_id){
        $media_img_data = MediaImgUtil::getRoomImgData($room_id);
        return $media_img_data;
    }
    
    // 5.edit
    public function edit($room_id){}
    // 6.update
    public static function update($room_id, $request){
        MediaImgUtil::updateRoomImgData($room_id, $request);
    }
    // 7.destroy
    public static function destroy($room_id){
        RoomImg::where('room_id', $room_id)->first()->delete();
    }
    
}
