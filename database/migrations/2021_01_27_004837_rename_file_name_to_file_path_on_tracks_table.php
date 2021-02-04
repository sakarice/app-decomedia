<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFileNameToFilePathOnTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            //
            $table->renameColumn('img_name', 'img_path');
            $table->renameColumn('sound_name', 'sound_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            //
            $table->renameColumn('img_path', 'img_name');
            $table->renameColumn('sound_path', 'sound_name');
        });
    }
}
