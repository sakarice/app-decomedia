<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Chat;
use App\Events\MessageCreated;

class ChatController extends Controller
{
    // 新着順にメッセージ一覧を取得
    public function index() {
        return Chat::orderby('id', 'desc')->get();
    }

    // メッセージを登録
    public function create(Request $request){
        $message = Chat::create([
            'user_id' => Auth::user()->id,
            'media_id' => 1, 
            'message' => $request->message
        ]);
        event(new MessageCreated($message));
    }

}
