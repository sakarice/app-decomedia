<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRoomlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roomlists', function (Blueprint $table) {
            //
            $table->integer('user_id')->after('id');
            $table->string('description', 191)->nullable()->after('name');
            $table->string('thumbnail_img_url', 191)->nullable()->after('description');
            $table->boolean('is_open')->default(false)->after('thumbnail_img_url');
            $table->boolean('is_chat_valid')->default(true)->after('is_open');
            $table->integer('enter_user_limit')->default(100)->nullable()->after('is_chat_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roomlists', function (Blueprint $table) {
            //
            $table->dropColumn('user_id');
            $table->dropColumn('description');
            $table->dropColumn('thumbnail_img_url');
            $table->dropColumn('is_open');
            $table->dropColumn('is_chat_valid');
            $table->dropColumn('enter_user_limit');
        });
    }
}
