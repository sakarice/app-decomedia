<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToRoomRoomlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_roomlists', function (Blueprint $table) {
            // 作成日・更新日のカラムを追加
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
        Schema::table('room_roomlists', function (Blueprint $table) {
            // 作成日・更新日カラムを削除
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
