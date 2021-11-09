<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAudio extends Model
{
    use HasFactory;

    protected $table = 'room_audios';

    public function rooms(){
        return $this->belongsToMany(
            'App\Models\Room'
        );
     }
}
