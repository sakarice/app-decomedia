<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('css/rooms.view.css')}}">
  <title>Document</title>
</head>
<body>
  <div class="room-title">
    <h1>{{$room_title}}</h1>
    user_id:{{$user_id}}

  </div>
  <div class="room-wrapper">
    <div class="track-list">
      <ul>
        @foreach($tracks as $track)
          <li>{{$track->title}}</li>
        @endforeach
      </ul>
    </div>
    <div class="room-img-wrapper">
      <img class="room-img" src="{{$tracks[0]->img_url}}" alt="room-image">
    </div>
    <div class="track-controller">
      <div class="track-title">track_title</div>
    </div>
  </div>
  <div class="buttons">
    <button>戻る</button>
    <button>更新</button>
    <button>公開</button>
  </div>
</body>
</html>