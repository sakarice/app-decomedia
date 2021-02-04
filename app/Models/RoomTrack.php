<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTrack extends Model
{
    use HasFactory;
    public $timestamps = false; // creted_at,updated_atカラムが無いため、自動タイムスタンプをオフに。
}
