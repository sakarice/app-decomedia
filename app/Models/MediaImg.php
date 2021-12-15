<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaImg extends Model
{
    use HasFactory;

    public function medias(){
        return $this->belongsToMany(
            'App\Models\Media'
        );
    }

    protected $fillable = [
        'media_id',
        'owner_user_id',
        'img_id',
        'img_type',
        'width',
        'height',
        'opacity',
        'img_layer',
    ];
    
}
