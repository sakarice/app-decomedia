<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MypageController extends Controller
{
    //test用メソッドを定義
    public function view(){

        // a.ビュー変数を準備
        $data = [
            'comment' => 'mypageテスト',
            'login_user_record' => User::find(Auth::id())
        ];
        // b.テンプレートを呼び出す
        return view('mypage.view', $data);
    }
}
