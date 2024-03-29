<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaSetting extends Model
{
    use HasFactory;

    protected $table = 'media_settings';

    public function medias(){
        return $this->belongsToMany(
            'App\Models\Media'
        );
     }

}
