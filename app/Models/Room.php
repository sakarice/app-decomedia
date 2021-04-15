<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // public function tracks(){
    //     return $this->belongsToMany('App\Models\Track', 'room_tracks');
    // }
}
