<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StereoPhonicArrange extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_audio_id',
        'panningFlag',
        'panningModel',
        'positionX',
        'positionY',
        'positionZ',
    ];

}
