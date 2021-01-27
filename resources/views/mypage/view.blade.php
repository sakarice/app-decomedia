<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('css/mypage.view.css') }}">
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
        <div class="preview_track">
          <img class="preview_img" src={{$trackUrlAndTitle['url']}} alt="">
          {{$trackUrlAndTitle['title']}}
        </div>
        @endforeach
        <a href="mypage/tracks">all tracks⇒</a>
      </div>
    </div>
  </div>
</body>
</html>