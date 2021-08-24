<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta id="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/mypage.view.css') }}">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>MyRoomList</title>
</head>
<body>

  <div class="contents-wrapper">

    <div id="app">
      {{-- ヘッダー --}}
      <header-component
      :csrf="{{json_encode(csrf_token())}}"
      :is-login="true">
      </header-component>

      <left-bar-component
      :csrf="{{json_encode(csrf_token())}}">
      </left-bar-component>

      <section class="created-room-list">
        <div class="section-top-wrapper">
          <h3 class="section-title">作成済みRoom</h3>
          {{-- Room作成 --}}
          <div class="room-create-wrapper">
            <a class="linkTo-createRoom" href="room/create">Room作成</a>
          </div>
        </div>
  
        {{-- 作成済みroom一覧 --}}
        <room-preview-component
          :room-preview-infos='@json($roomPreviewInfos,JSON_UNESCAPED_SLASHES)'
          :is-show-cover="true">
        </room-preview-component>    
      </section>

    </div>

  </div>
  <script src="{{ asset('js/mypage.js') }}"></script>
  <script src="{{ mix('/js/app.js') }}"></script>

</body>
</html>