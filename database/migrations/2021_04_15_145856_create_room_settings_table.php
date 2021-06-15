<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->string('name');
            $table->boolean('is_show_img');
            $table->boolean('is_show_movie');
            $table->integer('max_audio_num');
            $table->integer('background_type')->nullable();
            $table->string('background_color', 191);
            $table->boolean('chat_valid_flag')->nullable();
            $table->integer('open_state')->nullable();
            $table->integer('enter_limit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_settings');
    }
}
