<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta id="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/profile.view.css') }}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>profile</title>
</head>
<body>
  <div id="contents-wrapper">
    {{-- プロフィール --}}
    <div id="profile-wrapper">
      {{-- プロフィール画像 --}}
      <div class="profile-img-wrapper wrapper">
        @if(!$user->profile_img_url)  {{--プロフィール画像が未設定の場合は仮のアイコンを表示 --}}
          <img id="profile-img" src="{{asset('profile_img/user-solid.svg')}}" alt="">
        @else
          <img id="profile-img" src="{{$user->profile_img_url}}" alt="nasi">
        @endif
      </div>
      {{-- ユーザ名 --}}
      <div class="name-wrapper wrapper">
        <p id="name">{{$user->name}}</p>
      </div>
      {{-- プロフィール --}}
      <div class="profile-text-wrapper wrapper ">
        <p id="profile-text">{{$user->profile}}</p>
      </div>
    </div>

    {{-- リンク --}}
    <div id="link-wrapper">
      <a id="back-link link" class="link" href="javascript:history.back()">戻る</a>
      <a id="edit-link link" class="link" href="{{$user->id}}/edit">編集</a>
    </div>

  </div>


  <script src="{{ asset('js/profile.js')}}"></script>
</body>
</html>