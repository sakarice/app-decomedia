<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/rooms.view.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <title>Document</title>
</head>
<body>

  {{-- ボタン --}}
  <div class="buttons">
    <button><a href="javascript:history.back()">戻る</a></button>
    <button id="update-button">更新</button>
    <button>公開</button>
  </div>

  <div id="app">
    <room-component
    is-login='{{$isLogin}}'
    room-img-data='@json($room_img)'
    room-audios-data='@json($room_audios)'
    room-movie-data='@json($room_movie)'
    room-setting-data='@json($room_setting)'>
    </room-component>
  </div>

  
  {{-- <script src="{{ asset('js/view_room.js') }}"></script> --}}
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>