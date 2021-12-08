<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFigure extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'type',
        'user_selected_item_group_no',
        'left',
        'top',
        'width',
        'height',
        'degree',
        'global_alpha',
        'layer',
        'is_draw_fill',
        'fill_color',
        'is_draw_stroke',
        'stroke_color',
    ];
}
