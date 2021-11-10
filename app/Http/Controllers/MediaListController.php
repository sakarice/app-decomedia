<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Lib\StoreFileInS3;
use App\Lib\MediaListUtil;
use App\Lib\MediaMediaListUtil;
use App\Models\User;
use App\Models\Media;
use App\Models\Medialist;
use App\Models\MediaMedialist;

use Storage;

class MediaListController extends Controller
{
    // 1. index
    public function index() {}

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
