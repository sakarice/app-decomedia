<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaAudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_audios', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
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
        Schema::dropIfExists('media_audio');
    }
}
