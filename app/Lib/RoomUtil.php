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
use App\Lib\RoomImgUtil;
use App\Lib\DefaultImgUtil;
use App\Lib\UserOwnImgUtil;
use App\Lib\StoreFileInS3;
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

class RoomUtil
{


  // 指定のroom情報を取得
  public static function getRoomDatas($room_id){
    $room_img_data      = RoomImgController::show($room_id);
    $room_audios_data   = RoomAudioController::show($room_id);
    $room_movie_data    = RoomMovieController::show($room_id);
    $room_setting_data  = RoomSettingController::show($room_id);

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
    $rooms = Room::limit($record_num)
            ->where('user_id', $authenticated_userId)
            ->get();

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
  public static function getRoomPreviewInfo($room_id){
    $room_name = RoomSetting::where('room_id', $room_id)->first()->name;
    $room_img_url = RoomUtil::getRoomPreviewImgUrl($room_id);

    $roomInfo = array(
        'id' => $room_id,
        'name' => $room_name,
        'preview_img_url' => $room_img_url,
        'selectedOrderNum' => 0,
    );
    return $roomInfo;
  }


  // 画像、動画、音楽の設定状況に応じたプレビュー表示用画像を設定する
  // 優先度は、1:画像、2:動画、3:音楽 1,2,3どれもなければempty画像を設定
  public static function getRoomPreviewImgUrl($room_id){
    $room_img_url;
    $imgPattern = 0;
    if(RoomImg::where('room_id', $room_id)->exists() && RoomImg::where('room_id', $room_id)->first()->img_id != 0){
      $imgPattern = 1;
    } else if(RoomMovie::where('room_id', $room_id)->exists()){
      $imgPattern = 2;
    } else if(RoomBgm::where('room_id', $room_id)->exists()){
      $imgPattern = 3;
    }

    switch ($imgPattern){
      case 1:
        $room_img_id = RoomImg::where('room_id', $room_id)->first()->img_id;
        $room_img_type = RoomImg::where('room_id', $room_id)->first()->img_type;
        $room_img = RoomImgUtil::getRoomImgModel($room_img_id, $room_img_type);
        $room_img_url = $room_img->img_url;
        break;        
      case 2:
        $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/3oLdT6SSOkEUW0ejXRWsLX177aXQVOd5vRa8Qtse.png";
        break;
      case 3:
        $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/t6xoK6A2Wgy33J82wCzEvW12pnLqmeDkF4ASzqtO.jpg";
        break;
      default:
        $room_img_url = "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/tyOKqvszOb4LDP2egK6qTqWFzFiFnxlCurxaf98W.png";
    }
    
    return $room_img_url;
  }
    


  // room情報をDBに保存
  public static function saveRoomDataInDB($request){
    DB::beginTransaction();
    try{
      // room保存
      $room = new Room(); 
      $room->user_id = Auth::user()->id;
      $room->save();
      
      // 保存したroomのidを取得
      $room_id = Room::latest()->first()->id;
  
      // room画像
      if(isset($request->img['id'])){
        if($request->img['id'] != 0){
          $img_id = $request->img['id'];
          $isOwnImg = UserOwnImg::where('owner_user_id', Auth::user()->id)->where('id', $img_id)->exists();
          $isDefaultImg = DefaultImg::where('id', $img_id)->exists();
          if($isOwnImg || $isDefaultImg){
            RoomImgController::store($room_id, $request);
          }
        } else if($request->img['id'] == 0){ // room画像が設定されていなければ、仮情報を保存
          RoomImgUtil::saveTentativeRoomImgData($room_id);        
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

  // room情報を更新
  public static function updateRoomData($room_id, $request){
    DB::beginTransaction();
    try{

      // room画像
      if(isset($request->img['id'])){
        if($request->img['id'] != 0){
          $img_id = $request->img['id'];
          $isOwnImg = UserOwnImg::where('owner_user_id', Auth::user()->id)->where('id', $img_id)->exists();
          $isDefaultImg = DefaultImg::where('id', $img_id)->exists();
          if($isOwnImg || $isDefaultImg){
            RoomImgController::update($room_id, $request);
          }
        } else if($request->img['id'] == 0){ // room画像が設定されていなければ、仮情報を保存
          RoomImgUtil::updateRoomImgDataToTentative($room_id);        
        }
      }
      // room動画
      if(isset($request->movie['videoId'])){
        RoomMovieController::update($room_id, $request);
      }
      // room音楽
      if(isset($request->audios[0])){
        RoomAudioController::update($room_id, $request);
      }
      // room設定
      RoomSettingController::update($room_id, $request);      

      DB::commit();
    } catch(\Exception $e){
      DB::rollback();
    }

  }


  // room情報をDBから削除
  public static function deleteRoomDataFromDB($room_id){
    $user_id = Auth::user()->id;
    $returnMsg;
    DB::beginTransaction();
    try{
        Room::where('id', $room_id)
            ->where('user_id', $user_id)
            ->first()->delete();
        // Room画像
        RoomImgController::destroy($room_id);
        // Room音楽
        if(RoomBgm::where('room_id', $room_id)->exists()){                
            RoomAudioController::destroy($room_id);
        }
        // Room動画
        if(RoomMovie::where('room_id', $room_id)->exists()){
            RoomMovieController::destroy($room_id);
        }
    // Room設定
        RoomSettingController::destroy($room_id);

        DB::commit();
        $returnMsg = 'roomを削除しました';
    } catch(\Exception $e){
        DB::rollback();
        $returnMsg = 'roomの削除に失敗しました';
    }

    return $returnMsg;
  }


}