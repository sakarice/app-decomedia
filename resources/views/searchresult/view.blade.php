<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('css/searchresult.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}

  <title>Document</title>
</head>
<body>
  <h2>キーワード"{{$keyword}}"での検索結果</h2>
  <ul id="room-list-wrapper">
    @foreach($rooms as $room)
      <li class="room-list">
        <p class="room-id">{{$room->id}}</p>
        <img class="room-img" src={{$room->thumbnail_url}} alt="">
        <p>{{$room->title}}</p>
      </li>
    @endforeach
  </ul>

  <form id="room-id-form" method="POST" action="{{asset('/home/enterRoom')}}">
    @csrf
    <input id="room-id-input" type="hidden" name="room_id" value="">
  </form>

  <script src="{{ asset('js/search_result.js') }}"></script>

</body>
</html>