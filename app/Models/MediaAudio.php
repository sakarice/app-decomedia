<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaAudio extends Model
{
    use HasFactory;

    protected $table = 'media_audios';

    protected $fillable = [
        'media_id',
        'audio_id',
        'order_seq',
        'audio_type',
        'volume',
        'isLoop',
    ];

    public function medias(){
        return $this->belongsToMany(
            'App\Models\Media'
        );
    }
    
}
