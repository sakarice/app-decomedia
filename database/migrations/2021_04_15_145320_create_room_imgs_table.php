<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_imgs', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->integer('img_type'); // default or userOwn
            $table->integer('img_id');
            $table->integer('owner_user_id')->nullable();
            $table->integer('img_layer');
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
        Schema::dropIfExists('room_imgs');
    }
}
