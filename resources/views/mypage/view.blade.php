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
  <title>Mypage</title>
</head>
<body>
  <div class="header-wrapper">
    <a href="{{ asset('home') }}">ホーム画面へ</a>
    <img id="profile-img" src="{{asset('profile_img/user-solid.svg')}}" alt="">
  </div><br>
  <div class="page-title">マイページ</div><br>

  <div class="contents-wrapper">
    {{-- Room作成 --}}
    <div class="room-create-wrapper">
      <div class="create-contents">
        <div class="linkTo-createRoom">
          <a href="room/create">
            {{-- <div class="disk"></div> --}}
            <p style="font-size: 50px">Room作成</p>
          </a>
        </div>
      </div>
    </div>


    {{-- Room一覧 --}}
    {{-- <form method="GET" class="post-form" id="post-form" action="">
      <input type="hidden" id="room-post-input" name="room_id" value="">
    </form> --}}

    {{-- <div class="room-wrapper">
      @foreach($roomPreviewInfos as $roomPreviewInfo)
      <div class="preview-room" id={{$roomPreviewInfo['id']}}>
        <img class="room-thumbnail" src={{$roomPreviewInfo['preview_img_url']}} alt="">
        <div class="cover-menu">
          <a href="/home/room/{{$roomPreviewInfo['id']}}" class="cover-menu-link">
            <span class="link-title">閲覧</span>
          </a>
          <a href="mypage/editroom" class="cover-menu-link">
            <span class="link-title">編集</span>
          </a>
        </div>
        <i class="fas fa-trash del-icon"></i>
        <p class="room-title">{{$roomPreviewInfo['name']}}</p>
      </div>  
      @endforeach
    </div> --}}

    <div id="app">
      <cancel-button></cancel-button>
      <room-list-component
        :room-preview-infos='@json($roomPreviewInfos,JSON_UNESCAPED_SLASHES)'
        :is-show-cover="true">
        </room-list-component>
    </div>

  </div>
  <script src="{{ asset('js/mypage.js') }}"></script>
  <script src="{{ mix('/js/app.js') }}"></script>

</body>
</html>