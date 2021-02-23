@extends('layouts.trackEdit')

@section('css_link')
  {{ asset('/css/createtrack.css')}}
@endsection

@section('createOrUpdate', '作成')

@section('form-action', 'createtrack')

@section('track_id', "")

@section('msg')
  {{$msg}}
@endsection

@section('javascriptLink')
  {{ asset('/js/createtrack.js')}}
@endsection
