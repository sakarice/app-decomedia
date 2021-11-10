<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // テストユーザ登録。
        // 他ユーザのMedia閲覧や検索、チャットの確認をするため、複数人用意。
        $TEST_USER_NUM = 3;
        for($i = 1; $i <= $TEST_USER_NUM; $i++){
            \App\Models\User::create([
                'name' => 'test_user'.$i,
                'email' => 'testTest'.$i.'@gmail.com',
                'password' => 'testTest'.$i,
            ]);
        }
    }
}
