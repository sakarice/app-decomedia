<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOwnImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_own_imgs', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_user_id');
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
        Schema::dropIfExists('user_own_imgs');
    }
}
