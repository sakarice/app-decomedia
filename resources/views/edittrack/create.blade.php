@extends('layouts.trackEdit')

@section('css_link')
  {{ asset('/css/createtrack.css')}}
@endsection

@section('createOrUpdate', 'create')

@section('msg')
  {{$msg}}
@endsection

@section('javascriptLink')
  {{ asset('/js/createtrack.js')}}
@endsection
