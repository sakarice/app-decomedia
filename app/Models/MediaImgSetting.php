<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaImgSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_img_id',
        'type',
        'user_selected_item_group_no',
        'left',
        'top',
        'width',
        'height',
        'degree',
        'global_alpha',
        'layer',
    ];
}
