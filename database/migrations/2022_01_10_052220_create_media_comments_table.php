<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
            $table->integer('user_id');
            $table->string('user_name',191);
            $table->string('profile_img_url',191)->nullable();
            $table->string('comment',191);
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
        Schema::dropIfExists('media_comments');
    }
}
