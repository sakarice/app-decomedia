<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MakeTrackController extends Controller
{
    //
    public function index() {
        $data = [
            'msg' => 'messssaaaaagggggeeeeee!'
        ];
        return view('maketrack.index', $data);
    }


    public function uploadfile(Request $request) {
        // $imgfile = $request->file('img');
        // $imgfileName = $imgfile->getClientOriginalName();
        // $imgfile->storeAs('img', $imgfileName, 'public');

        // $audiofile = $request->file('audio');
        // $audiofileName = $audiofile->getClientOriginalName();
        // $audiofile->storeAs('audio', $audiofileName, 'public');

        $this->saveFile($request, 'img');
        $this->saveFile($request, 'audio');

        // $data = [];
        return view('maketrack.index');
        
    }
    
    private function saveFile($request, $fileType){
        $file = $request->file($fileType);
        $fileName = $file->getClientOriginalName();
        $file->storeAs($fileType, $fileName, 'public');
    }


}
