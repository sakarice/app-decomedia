<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/medias.view.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <title>Document</title>
</head>
<body>

  <div id="app">
    {{-- <media-edit-component>
    </media-edit-component> --}}
    <router-view></router-view>
  </div>

  
  <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>