<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicAudioThumbnailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_audio_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_user_id')->default(NULL)->nullable();
            $table->string('name', 191);
            $table->string('img_path', 191);
            $table->string('img_url', 191);
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
        Schema::dropIfExists('public_audio_thumbnails');
    }
}
