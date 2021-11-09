<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function settings(){
        return $this->hasOne('App\Models\MediaSetting');
    }

    public function room_roomlists(){
        return $this->belongsToMany(
            'App\Models\RoomList',
            'room_roomlists',
        );
    }

    
}
