<?php

namespace App\Http\Controllers\RoomImg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\StoreFileInS3;
use App\Lib\ImgUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnImg;
use App\Models\DefaultImg;
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
        \Log::info('img保存開始');
        $user_id = Auth::user()->id;
        $roomImg = new RoomImg();
        $roomImg->room_id = $room_id;
        $room_img_type = $request->img['type'];
        $roomImg->img_type = $room_img_type;
        $roomImg->img_id = $request->img['id'];
        $roomImg->width = $request->img['width'];
        $roomImg->height = $request->img['height'];
        $roomImg->opacity = $request->img['opacity'];
        $roomImg->owner_user_id = $user_id;
        $roomImg->img_layer = $request->img['layer'];
        \Log::info($request->img);
        $roomImg->save();
    
        \Log::info('img保存完了');
    }

    // 4.show
    public static function show($room_id){
        // 〇 room画像が設定されている場合⇒DBから情報取得
        if(RoomImg::where('room_id', $room_id)->exists()){
            $room_img = RoomImg::where('room_id', $room_id)->first();
            $room_img_id = $room_img->img_id;
            $room_img_type = $room_img->img_type;
            $room_img_url = ImgUtil::getRoomImgUrlByType($room_id, $room_img_type);
            $room_img_width = $room_img->width;
            $room_img_height = $room_img->height;
            $room_img_opacity = $room_img->opacity;
            $room_img_layer = $room_img->img_layer;
        // × room画像が設定されていない場合⇒デフォルト値をセット
        } else {            
            $room_img_id
            = $room_img_type
            = $room_img_width
            = $room_img_height
            = $room_img_layer
            = 0;
            $room_img_opacity = 1;
            $room_img_url = "";
        }

        // room画像情報を格納した連想配列を作成
        $room_img_data = [
            'id' => $room_img_id,
            'type' => $room_img_type,
            'url' => $room_img_url,
            'width' => $room_img_width,
            'height' => $room_img_height,
            'opacity' => $room_img_opacity,
            'layer' => $room_img_layer,
        ];

        return $room_img_data;
    }
    
    // 5.edit
    public function edit($room_id){}
    // 6.update
    public function update($room_id){}
    // 7.destroy
    public static function destroy($room_id){
        RoomImg::where('room_id', $room_id)->first()->delete();
    }
    
}