<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ImgController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\RoomImgController;
use App\Http\Controllers\RoomAudioController;
use App\Http\Controllers\RoomMovieController;
use App\Http\Controllers\RoomSettingController;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Lib\RoomUtil;
use App\Models\User;
use App\Models\Room;
use App\Models\Roomlist;
use App\Models\RoomRoomlist;

use Storage;

class RoomListUtil
{

  // 指定のroom情報を取得
  public static function getRoomDatas($room_id){
    // room画像情報をDBから取得
    $room_img_data = RoomImgController::show($room_id);

    // room音楽情報をDBから取得
    $room_audios_data = RoomAudioController::show($room_id);

    // room動画情報をDBから取得
    $room_movie_data = RoomMovieController::show($room_id);

    // room設定情報をDBから取得
    $room_setting_data = RoomSettingController::show($room_id);

    $data = [
        'room_img' => $room_img_data,
        'room_audios' => $room_audios_data,
        'room_movie' => $room_movie_data,
        'room_setting' => $room_setting_data,
    ];

    return $data;

  }

  // 作成済みroomのプレビュー情報を取得
  public static function getCreatedRoomPreviewInfos($record_num=100){
    $authenticated_userId = Auth::user()->id;
    $createdRoomPreviewInfos = array();
    $rooms = Room::limit($record_num)->where('user_id', $authenticated_userId)->get();
    foreach($rooms as $index => $room){
        $room_id = $room->id;
        $createdRoomPreviewInfos[] = RoomUtil::getRoomPreviewInfo($room_id);
    }

    return ['createdRoomPreviewInfos' => $createdRoomPreviewInfos];
  }

  // いいねしたroomのプレビュー情報を取得
  public static function getLikedRoomPreviewInfos($record_num=100){
    $authenticated_userId = Auth::user()->id;
    $likedRoomPreviewInfos = array();
    $rooms = RoomUtil::getLikedRoomModel($authenticated_userId, $record_num);
    foreach($rooms as $index => $room){
        $room_id = $room->room_id; // ※idではなくroom_idで指定する
        $likedRoomPreviewInfos[] = RoomUtil::getRoomPreviewInfo($room_id);
    }
    return ['likedRoomPreviewInfos' => $likedRoomPreviewInfos];
  }
  

  // 自分がいいねしたroomのmodelを返す
  public static function getLikedRoomModel($user_id, $record_num){
    $sql = <<< SQL
      SELECT * FROM rooms r
        INNER JOIN user_like_rooms ulr
        ON ulr.room_id = r.id
        WHERE ulr.user_id = $user_id
        LIMIT $record_num
    SQL;

    $rooms = DB::select($sql);
    return $rooms;  
  }

  // 公開中のroomのmodelを返す
  public static function getOpenRoomModel($record_num){
    $sql = <<< SQL
      SELECT * FROM rooms r
        INNER JOIN room_settings rs
        ON rs.room_id = r.id
        WHERE rs.open_state = 1
        LIMIT $record_num
    SQL;

    $rooms = DB::select($sql);
    return $rooms;
  }


    // Roomのプレビュー表示に必要な情報を取得(id,title,サムネ画像のurl)
    public static function getRoomListPreviewInfo($room_list_id){
      $name = Roomlist::find($room_list_id)->name;
      $thumbnail_img_url = Roomlist::find($room_list_id)->thumbnail_img_url;

      $roomListInfo = array(
          'id' => $room_list_id,
          'name' => $name,
          'preview_img_url' => $thumbnail_img_url,
      );
      return $roomListInfo;
    }
    


  // room情報をDBに保存
  public static function saveRoomDataInDB($request){
    DB::beginTransaction();
    try{
      $user_id = Auth::user()->id;
      // room保存
      $room = new Room(); 
      $room->user_id = $user_id;
      $room->save();
      
      // 保存したroomのidを取得
      $room_id = Room::latest()->first()->id;

      // room画像
      if(isset($request->img['id'])){
        if($request->img['id'] !== ""){
          RoomImgController::store($room_id, $request);
        }
      }
  
      // room動画
      if(isset($request->movie['videoId'])){
        RoomMovieController::store($room_id, $request);
      }
  
      // room音楽
      if(isset($request->audios[0])){
        RoomAudioController::store($room_id, $request);
      }

      // room設定
      RoomSettingController::store($room_id, $request);

      DB::commit();

    } catch(\Exception $e){
      DB::rollback();
    }
  }


  // room情報をDBから削除
  public static function deleteRoomListDataFromDB($room_list_id){
    $user_id = Auth::user()->id;
    Roomlist::where('id', $room_list_id)
      ->where('user_id', $user_id)
      ->first()
      ->delete();
  }


}