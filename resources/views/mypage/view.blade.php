<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {{-- <meta id="csrf-token" content="{{ csrf_token() }}"> --}}
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

    <div class="room-wrapper">
      @foreach($roomInfos as $roomInfo)
      <div class="preview-room" id={{$roomInfo['id']}}>
        <img class="room-thumbnail" src={{$roomInfo['img_url']}} alt="">
        <div class="cover-menu">
          <a href="/home/room/{{$roomInfo['id']}}" class="cover-menu-link">
            <span class="link-title">閲覧</span>
          </a>
          <a href="mypage/editroom" class="cover-menu-link">
            <span class="link-title">編集</span>
          </a>
          {{-- <input class="cover-menu-link" form="post-form" type="submit" value="show"> --}}
          {{-- <input class="cover-menu-link" form="post-form" type="submit" value="edit"> --}}
        </div>
        <i class="fas fa-trash del-icon"></i>
        {{-- <img class="del-icon" src="{{ asset('icon/ゴミ箱アイコン.svg') }}" alt=""> --}}
        <p class="room-title">{{$roomInfo['name']}}</p>
      </div>  
      @endforeach
    </div>
  </div>
  <script src="{{ asset('js/mypage.js') }}"></script>
</body>
</html>