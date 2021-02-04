<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta id="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/mypage.view.css') }}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>Mypage</title>
</head>
<body>
  <div class="profile-img-wrapper">
    <img class="profile-img" src="{{asset('profile_img/user-solid.svg')}}" alt="">
  </div><br>
  <div class="page-title">マイページ</div><br>

  <div class="contents-wrapper">
    <div class="track-wrapper">
      <div class="track-create-wrapper">
        {{-- トラック作成 --}}
        <div class="create-contents">
          <div class="linkTo-createTrack">
            <a href="mypage/createtrack">
              <div class="disk"></div>
              <p style="font-size: 50px">track作成</p>
            </a>
          </div>
        </div>
      </div>
      {{-- 作成済みトラック一覧 --}}
      <div class="track-show-wrapper">
        <h3 class="preview-track-title">作成済みトラック</h3>
        @foreach($trackUrlAndTitles as $trackUrlAndTitle)
        <div class="preview-track">
          <img class="preview-img" src={{$trackUrlAndTitle['url']}} alt="">
          {{$trackUrlAndTitle['title']}}
        </div>
        @endforeach
        <a href="mypage/tracks">all tracks⇒</a>
      </div>
    </div>

    {{-- Room一覧 --}}
    <div class="room-wrapper">
      @foreach($roomInfos as $roomInfo)
      <div class="preview-room" id={{$roomInfo['id']}}>
        <img class="room-thumbnail" src={{$roomInfo['url']}} alt="">
        <div class="cover-menu">
          <a class="cover-menu-link" href="">show</a>
          <a class="cover-menu-link" href="">edit</a>
          <img class="del-icon" src="{{ asset('icon/ゴミ箱アイコン.svg') }}" alt="">
        </div>
        <p class="room-title">{{$roomInfo['title']}}</p>
      </div>
      @endforeach
    </div>
  </div>
  <script src="{{ asset('js/mypage.js') }}"></script>
</body>
</html>