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

  {{-- <div class="action-button-wrapper">
    <a href="#" onclick="javascript:window.history.back(-1); return false;">
      <button class="cancel-button">
        戻る
      </button>
    </a>
  </div> --}}


  <div id="app">
    {{-- ヘッダー --}}
    <header-component
    :csrf="{{json_encode(csrf_token())}}"
    :is-login=@json($isLogin)>
    </header-component>
    
    {{-- 検索結果一覧 --}}
    <section class="search-result">
      <h2 class="search-result-message">キーワード"{{$keyword}}"での検索結果</h2>
  
      <room-preview-component
      :room-preview-infos=@json($roomPreviewInfos)
      :is-show-cover="false">
      </room-preview-component>
    </section>
  
  </div>


  <form id="room-id-form" method="POST" action="{{asset('/home/enterRoom')}}">
    @csrf
    <input id="room-id-input" type="hidden" name="room_id" value="">
  </form>

  <script src="{{ asset('js/search_result.js') }}"></script>
  <script src="{{ mix('/js/app.js') }}"></script>


</body>
</html>