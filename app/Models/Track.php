<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    public function rooms(){
        return $this->belongsToMany('App\Models\Room', 'room_tracks');
    }

}
