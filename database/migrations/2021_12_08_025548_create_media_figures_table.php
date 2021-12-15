<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaFiguresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_figures', function (Blueprint $table) {
            $table->id();
            $table->integer('media_id');
            $table->integer('type');
            $table->integer('user_selected_item_group_no')->nullable()->default(NULL);
            $table->integer('left');
            $table->integer('top');
            $table->integer('width');
            $table->integer('height');
            $table->float('degree', 4, 1)->default(1);
            $table->float('global_alpha', 3, 2)->default(1);
            $table->integer('layer');
            $table->boolean('is_draw_fill');
            $table->string('fill_color', 191)->nullable();
            $table->boolean('is_draw_stroke');
            $table->string('stroke_color', 191)->nullable();

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
        Schema::dropIfExists('media_figures');
    }
}
