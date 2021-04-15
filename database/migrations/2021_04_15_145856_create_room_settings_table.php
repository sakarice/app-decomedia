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
            $table->integer('background_type');
            $table->boolean('chat_valid_flag');
            $table->integer('open_state');
            $table->integer('enter_limit');
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
