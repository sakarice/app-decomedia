<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('css/searchresult.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}

  <title>publicImg</title>
</head>
<body>


  <div id="app">
    <public-img-component>
    </public-img-component>  
  </div>

  <script src="{{ mix('/js/app.js') }}"></script>


</body>
</html>