<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_imgs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(NULL);
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
        Schema::dropIfExists('default_imgs');
    }
}
