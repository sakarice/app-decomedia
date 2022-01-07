<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_texts', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
            $table->integer('type');
            $table->integer('user_selected_item_group_no')->nullable()->default(NULL);
            $table->string('text', 191)->nullable();
            $table->integer('left');
            $table->integer('top');
            $table->integer('width');
            $table->integer('height');
            $table->float('scale_x', 4, 1)->default(1);
            $table->float('scale_y', 4, 1)->default(1);
            $table->string('color', 191)->nullable();
            $table->integer('font_size');
            $table->string('font_category', 191)->nullable();
            $table->string('font_family', 191)->nullable();
            $table->float('degree', 4, 1)->default(1);
            $table->float('opacity', 3, 2)->default(1);
            $table->integer('layer');
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
        Schema::dropIfExists('media_texts');
    }
}
