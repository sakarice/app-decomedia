<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaMovie extends Model
{
    use HasFactory;

    public function medias(){
        return $this->belongsToMany(
            'App\Models\Media'
        );
     }

    protected $fillable = [
        'id',
        'media_id',
        'user_id',
        'video_id',
        'left',
        'top',
        'width',
        'height',
        'opacity',
        'movie_layer',
        'isLoop',
    ];
}
