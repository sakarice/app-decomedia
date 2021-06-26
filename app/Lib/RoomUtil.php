<?php

namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\ImgUtil;
use App\Lib\StoreFileInS3;
use App\Http\Controllers\Img\ImgController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RoomSettingController;
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
    // room画像情報をDBから取得
    $room_img_data = ImgUtil::getRoomImgData($room_id);

    // room音楽情報をDBから取得
    $room_audios_data = AudioController::getRoomAudiosData($room_id);

    // room動画情報をDBから取得
    $room_movie_data = MovieController::getRoomMovieData($room_id);

    // room設定情報をDBから取得
    $room_setting_data = RoomSettingController::getRoomSettingData($room_id);

    $data = [
        'room_img' => $room_img_data,
        'room_audios' => $room_audios_data,
        'room_movie' => $room_movie_data,
        'room_setting' => $room_setting_data,
    ];

    return $data;

  }

  // room情報をDBから削除
  public static function deleteRoomDataFromDB($room_id){
    $user_id = Auth::user()->id;

    DB::beginTransaction();
    try{
        // Room
        Room::where('id', $room_id)
            ->where('user_id', $user_id)
            ->first()
            ->delete();

        // Room画像
        if(RoomImg::where('room_id', $room_id)->exists()){
            RoomImg::where('room_id', $room_id)->first()->delete();
        }

        // Room音楽
        if(RoomBgm::where('room_id', $room_id)->exists()){                
            RoomBgm::where('room_id', $room_id)->delete();
        }

        // Room動画
        if(RoomMovie::where('room_id', $room_id)->exists()){
            RoomMovie::where('room_id', $room_id)->first()->delete();
        }
        
        // Room設定
        RoomSetting::where('room_id', $room_id)->first()->delete();

        DB::commit();

      } catch(\Exception $e){
        DB::rollback();
    }    

  }


  // room情報をDBに保存
  public static function saveRoomDataInDB($request){

    DB::beginTransaction();
    try{
      $user_id = Auth::user()->id;

      // room保存
      $room = new Room();
      $room->user_id = $user_id;
      if(isset($request->setting['name'])){
          $room->name = $request->setting['name'];
      }else {
          $room->name = 'room';
      }
      $room->save();
  
  
      // 保存したroomのidを取得
      $room_id = Room::latest()->first()->id;
      \Log::info($room_id);
  
      // room画像
      if(isset($request->img['id'])){
          \Log::info('img保存開始');
  
          $roomImg = new RoomImg();
          $roomImg->room_id = $room_id;
          $room_img_type;
          if($request->img['type'] == 'default-img'){
              $room_img_type = 1; // default
          } else if($request->img['type'] == 'user-own-img'){
              $room_img_type = 2; // userOwn
          }
          $roomImg->img_type = $room_img_type;
          $roomImg->img_id = $request->img['id'];
          // $roomImg->img_url = $request->img['url'];
          $roomImg->width = $request->img['width'];
          $roomImg->height = $request->img['height'];
          $roomImg->owner_user_id = $user_id;
          $roomImg->img_layer = $request->img['layer'];
          $roomImg->save();
      }
  
      \Log::info('img保存スルーor完了');
  
  
      // room動画
      if(isset($request->movie['videoId'])){
          \Log::info('movie保存開始');
  
          $roomMovie = new RoomMovie();
          $roomMovie->user_id = $user_id;
          $roomMovie->room_id = $room_id;
          $roomMovie->video_id = $request->movie['videoId'];
          $roomMovie->width = $request->movie['width'];
          $roomMovie->height = $request->movie['height'];
          $roomMovie->isLoop = $request->movie['isLoop'];
          $roomMovie->movie_layer = $request->movie['layer'];
          $roomMovie->save();
      } 
      \Log::info('動画ID'.$request->movie['videoId']);
  
  
  
      // room音楽
      if(isset($request->audios[0])){
          \Log::info('audio保存開始');
          
          $roomAudios = $request->audios;
          foreach($roomAudios as $index => $roomAudio){
              $roomBgm = new RoomBgm();
              $audio_id;
              if($roomAudio['type'] == 1){
                  $audio_id = DefaultBgm::where('audio_url', $roomAudio['url'])
                                          ->first()
                                          ->id;
              } else if ($roomAudio['type'] == 2){
                  $audio_id = UserOwnBgm::where('audio_url', $roomAudio['url'])
                                          ->first()
                                          ->id;
              }
  
              $roomBgm->room_id = $room_id;
              $roomBgm->audio_type = $roomAudio['type'];
              $roomBgm->audio_id = $audio_id;
              $roomBgm->order_seq = $index + 1;
              $roomBgm->volume = $roomAudio['volume'];
              $roomBgm->isLoop = $roomAudio['isLoop'];
              if($roomAudio['type'] == 2){ //2:ユーザのアップロードした音楽
                  $roomBgm->owner_user_id = $user_id;
              } // 2以外はdefaultのオーディオ(=ユーザのものでない）ので、NULLで良い
  
  
              $roomBgm->save();    
          }
      }
  
      // room設定
      $roomSetting = new RoomSetting();
      $roomSetting->room_id = $room_id;
      $roomSetting->is_show_img = $request->setting['isShowImg'];
      $roomSetting->is_show_movie = $request->setting['isShowMovie'];
      $roomSetting->max_audio_num = $request->setting['maxAudioNum'];
      // $roomSetting->background_type = $request->setting['roomBackgroundType'];
      $roomSetting->background_color = $request->setting['roomBackgroundColor'];
      $roomSetting->save();

      DB::commit();

    } catch(\Exception $e){
      DB::rollback();
    }


    
  }


}