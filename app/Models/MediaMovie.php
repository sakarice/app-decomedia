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
}
