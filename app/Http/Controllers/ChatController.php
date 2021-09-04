<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Storage;
use App\Models\User;


class ChatController extends Controller
{
    // ★チャット機能実装テスト用。
    public function index(){
        return view('chat.view');
    }

}
