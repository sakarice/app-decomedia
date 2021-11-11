<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_imgs', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
            $table->integer('img_type'); // default or userOwn
            $table->integer('img_id');
            $table->integer('width');
            $table->integer('height');
            $table->float('opacity', 3, 2);
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
        Schema::dropIfExists('media_imgs');
    }
}
