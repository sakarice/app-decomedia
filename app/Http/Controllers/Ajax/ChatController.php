<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageCreated;

class ChatController extends Controller
{
    // 新着順にメッセージ一覧を取得
    public function index() {
        return Message::orderby('id', 'desc')->get();
    }

    // メッセージを登録
    public function create(Request $request){
        // デバッグ用ログ出力 後で消す
        \Log::info('called create');

        $message = Message::create([
            'user_id' => Auth::user()->id,
            'room_id' => 1, 
            'message' => $request->message
        ]);
        \Log::info($message);
        event(new MessageCreated($message));
    }

}
