<?php

namespace App\Http\Controllers\RoomList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Lib\RoomUtil;
use App\Lib\RoomListUtil;
use App\Lib\RoomRoomListUtil;
use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\UserOwnBgm;
use App\Models\DefaultImg;
use App\Models\DefaultBgm;
use App\Models\Room;
use App\Models\Roomlist;
use App\Models\RoomRoomlist;
use App\Models\RoomImg;
use App\Models\RoomBgm;
use App\Models\RoomMovie;
use App\Models\RoomSetting;

use Storage;

class RoomListController extends Controller
{
    // 1. index
    public function index() {
        if(Auth::check()){
            $checked = "ユーザー：".Auth::user()->name."は認証済みです";
            $data = [
                'msg' => $checked,
            ];
        }
    }

    // 2. create
    public function create(){}

    // 3. store
    public function store(){}

    // 4. show
    public function show(){}

    // 5. edit
    public function edit(){}

    // 6. update
    public function update(){}

    // 7. destroy
    public function destroy(){}

}
