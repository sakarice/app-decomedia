<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaImgSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_img_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('media_img_id');
            $table->integer('type');
            $table->integer('user_selected_item_group_no')->nullable()->default(NULL);
            $table->integer('left');
            $table->integer('top');
            $table->integer('width');
            $table->integer('height');
            $table->float('degree', 4, 1)->default(1);
            $table->float('global_alpha', 3, 2)->default(1);
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
        Schema::dropIfExists('media_img_settings');
    }
}
