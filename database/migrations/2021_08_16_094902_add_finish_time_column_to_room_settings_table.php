<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishTimeColumnToRoomSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_settings', function (Blueprint $table) {
            //
            $table->integer('finish_time')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_settings', function (Blueprint $table) {
            //
            $table->dropColumn('finish_time');
        });
    }
}
