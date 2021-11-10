<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
            $table->string('name', 191);
            $table->string('description', 191)->nullable();
            $table->integer('finish_time')->nullable();
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
        Schema::dropIfExists('media_settings');
    }
}
