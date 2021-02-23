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
  <div class="contents_wrapper">
    <div class="header">マイページ</div><br>
    <div class="contents">
      <div class="profile_img_wrapper">
        <img class="profile_img" src="{{asset('profile_img/user-solid.svg')}}" alt="">
      </div><br>
      <div class=profile>
        {{$comment}}<br>
        id:{{$login_user_record->id}}<br>
        ユーザー名:{{$login_user_record->name}}<br>
        mail:{{$login_user_record->email}}<br>
      </div><br>
      <div class="create-contents" style="text-align: center">
        <div class="toCreateTrack">
          <a href="mypage/createtrack" style="font-size: 40px">track作成</a>
        </div>
      </div>

    </div>
  </div>
</body>
</html>