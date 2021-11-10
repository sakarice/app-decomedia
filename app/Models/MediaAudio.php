<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaAudio extends Model
{
    use HasFactory;

    protected $table = 'media_audios';

    public function medias(){
        return $this->belongsToMany(
            'App\Models\Media'
        );
     }
}
