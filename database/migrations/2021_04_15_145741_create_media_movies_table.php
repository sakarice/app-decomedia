<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_movies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('media_id');
            $table->string('video_id', 191);
            $table->integer('width');
            $table->integer('height');
            $table->integer('isLoop');
            $table->integer('movie_layer');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_movies');
    }
}