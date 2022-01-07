<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaContentsField extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'width',
        'height',
        'color',
        'img_id',
        'img_type',
        'img_url',
        'img_size_type',
    ];
}
