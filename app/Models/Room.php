<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // public function room_imgs(){
    //     return $this->belongsToMany('App\Models\RoomImg');
    // }

    // public function room_bgms(){
    //     return $this->belongsToMany('App\Models\RoomBgm');
    // }

    // public function room_movies(){
    //     return $this->belongsToMany('App\Models\RoomMovie');
    // }

    public function settings(){
        return $this->hasOne('App\Models\RoomSetting');
    }

    public function room_roomlists(){
        return $this->belongsToMany(
            'App\Models\RoomList',
            'room_roomlists',
        );
    }

    
}
