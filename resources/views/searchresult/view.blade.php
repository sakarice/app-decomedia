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

  <div class="action-button-wrapper">
    <a href="#" onclick="javascript:window.history.back(-1); return false;">
      <button class="cancel-button">
        戻る
      </button>
    </a>
  </div>

  <h2>キーワード"{{$keyword}}"での検索結果</h2>

  <div id="app">
    <room-list-component
    :room-preview-infos=@json($roomPreviewInfos)
    :is-show-cover="false">
    </room-list-component>
  </div>

{{-- 
  <ul id="room-list-wrapper">
    @foreach($roomPreviewInfos as $roomPreviewInfo)
      <li class="room-list">
        <p class="room-id">{{$roomPreviewInfo['id']}}</p>
        <img class="room-img" src={{$roomPreviewInfo['preview_img_url']}} alt="">
        <p>{{$roomPreviewInfo['name']}}</p>
      </li>
    @endforeach
  </ul> --}}

  <form id="room-id-form" method="POST" action="{{asset('/home/enterRoom')}}">
    @csrf
    <input id="room-id-input" type="hidden" name="room_id" value="">
  </form>

  <script src="{{ asset('js/search_result.js') }}"></script>
  <script src="{{ mix('/js/app.js') }}"></script>


</body>
</html>