<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultBgmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_bgms', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(NULL);
            $table->string('name', 191);
            $table->string('audio_path', 191);
            $table->string('audio_url', 191);
            $table->string('thumbnail_path', 191);
            $table->string('thumbnail_url', 191);
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
        Schema::dropIfExists('default_bgms');
    }
}
