<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaContentsFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_contents_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
            $table->integer('width');
            $table->integer('height');
            $table->string('color',191)->default('#ffffff'); // 白
            $table->string('img_url',191)->nullable();
            $table->string('img_size_type',191)->default('cover');
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
        Schema::dropIfExists('media_contents_fields');
    }
}
