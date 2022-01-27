<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserOwnAudiosTableColumnTypeStringToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_own_audios', function (Blueprint $table) {
            $table->text('thumbnail_url')->change();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_own_audios', function (Blueprint $table) {
            $table->string('thumbnail_url', 191)->change();
            //
        });
    }
}
