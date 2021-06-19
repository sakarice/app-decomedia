<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomBgmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_bgms', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->integer('audio_type'); // default or userOwn
            $table->integer('audio_id');
            $table->integer('order_seq');
            $table->double('volume', 3, 2);
            $table->boolean('isLoop');
            $table->integer('owner_user_id')->nullable();
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
        Schema::dropIfExists('room_bgms');
    }
}
