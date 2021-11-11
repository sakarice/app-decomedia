<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedialistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medialists', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 191);
            $table->string('description', 191)->nullable();
            $table->string('thumbnail_img_url', 191)->nullable();
            $table->boolean('is_open')->default(false);
            $table->boolean('is_chat_valid')->default(true);
            $table->integer('enter_user_limit')->default(100)->nullable();
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
        Schema::dropIfExists('medialists');
    }
}
