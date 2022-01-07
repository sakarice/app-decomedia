<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaText extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'type',
        'user_selected_item_group_no',
        'text',
        'left',
        'top',
        'width',
        'height',
        'scale_x',
        'scale_y',
        'color',
        'font_size',
        'font_category',
        'font_family',
        'degree',
        'opacity',
        'layer',
    ];

}
