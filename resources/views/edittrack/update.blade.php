@extends('layouts.trackEdit')

@section('css_link')
  {{ asset('/css/createtrack.css')}}
@endsection

@section('createOrUpdate', '更新')

@section('form-action', 'trackupdating')
    
@section('inner-display')
  @if($img_url!="")
    <img id="preview" src="{{$img_url}}" alt="">
  @else
    <div>画像or音声ファイル<br>ドラッグ&ドロップ</div>
  @endif
@endsection

@section('track-title')
{{$track_title}}
@endsection

@section('javascriptLink')
  {{ asset('/js/createtrack.js')}}
@endsection
