<?php

namespace App\Http\Controllers\RoomAudio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lib\storeFileInS3;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOwnBgm;
use App\Models\DefaultBgm;
use App\Models\RoomBgm;
use Storage;


class RoomAudioController extends Controller
{
        // 1.index
        public function index(){}
        // 2.create
        public function create(Request $request){}
        // 3.store
        public function store(Request $request){}

        // 4.show
        public static function show($room_id){
            $room_bgm_id;
            $room_bgm_type;
            $room_bgm_audio_url;
            $room_bgm_thumbnail_url;
            $room_bgms_data = array();
    
            if(RoomBgm::where('room_id', $room_id)->exists()){
                $room_bgms = RoomBgm::where('room_id', $room_id)->get();
                foreach($room_bgms as $room_bgm){
                    $room_bgm_id = $room_bgm->audio_id;
                    $room_bgm_type = $room_bgm->audio_type;
                    if($room_bgm_type == 1){
                        $room_bgm_audio_url = DefaultBgm::where('id', $room_bgm_id)->first()->audio_url;
                        $room_bgm_thumbnail_url = DefaultBgm::where('id', $room_bgm_id)->first()->thumbnail_url;
                    }else if($room_bgm_type == 2){
                        $room_bgm_audio_url = UserOwnBgm::where('id', $room_bgm_id)->first()->audio_url;
                        $room_bgm_thumbnail_url = UserOwnBgm::where('id', $room_bgm_id)->first()->thumbnail_url;
                    }
                }
    
                $room_bgm_data = [
                    'id' => $room_bgm_id,
                    'type' => $room_bgm_type,
                    'audio_url' => $room_bgm_audio_url,
                    'thumbnail_url' => $room_bgm_thumbnail_url,
                    'volume' => $room_bgm->volume,
                    'isLoop' => $room_bgm->isLoop,
                ];
        
                $room_bgms_data[] = $room_bgm_data;
            
            }
    
            return $room_bgms_data;
    
        }

        // 5.edit
        public function edit($id){}
        // 6.update
        public function update($id){}
        // 7.destroy
        public function destroy($id){}
    


    // // room画像の情報をDBから取得し、配列を返す。
    // public static function getRoomAudiosData($room_id){
    //     $room_bgm_id;
    //     $room_bgm_type;
    //     $room_bgm_audio_url;
    //     $room_bgm_thumbnail_url;
    //     $room_bgms_data = array();

    //     if(RoomBgm::where('room_id', $room_id)->exists()){
    //         $room_bgms = RoomBgm::where('room_id', $room_id)->get();
    //         foreach($room_bgms as $room_bgm){
    //             $room_bgm_id = $room_bgm->audio_id;
    //             $room_bgm_type = $room_bgm->audio_type;
    //             if($room_bgm_type == 1){
    //                 $room_bgm_audio_url = DefaultBgm::where('id', $room_bgm_id)->first()->audio_url;
    //                 $room_bgm_thumbnail_url = DefaultBgm::where('id', $room_bgm_id)->first()->thumbnail_url;
    //             }else if($room_bgm_type == 2){
    //                 $room_bgm_audio_url = UserOwnBgm::where('id', $room_bgm_id)->first()->audio_url;
    //                 $room_bgm_thumbnail_url = UserOwnBgm::where('id', $room_bgm_id)->first()->thumbnail_url;
    //             }
    //         }

    //         $room_bgm_data = [
    //             'id' => $room_bgm_id,
    //             'type' => $room_bgm_type,
    //             'audio_url' => $room_bgm_audio_url,
    //             'thumbnail_url' => $room_bgm_thumbnail_url,
    //             'volume' => $room_bgm->volume,
    //             'isLoop' => $room_bgm->isLoop,
    //         ];
    
    //         $room_bgms_data[] = $room_bgm_data;
        
    //     }

    //     return $room_bgms_data;


    // }

}
