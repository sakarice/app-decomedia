<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/medias.view.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>Document</title>
</head>
<body>
  <div class="room-title-wrapper">
    <p id="room-id" style="display: none">{{$room_id}}</p>
    <p id="room-title">{{$room_title}}</p>
  </div>
  <div class="room-and-track-wrapper">
    {{-- room情報表示 --}}
    <div class="room-wrapper">
      {{-- trackリスト --}}
      <div class="room-tracks-wrapper">
        <div class="room-track-list-cover"></div>
        <ul id="room-track-list" class="room-track-list">
          @foreach($room_tracks as $index => $room_track)
            <li class="track-info">
              <div class="track-info-cover"></div>
              <div class="icon-and-index">
                <img class="track-remove-icon" src="{{ asset('icon/マイナスマークアイコン 6.svg')}}" alt="-">
                <span class="track-index">{{$index+1}}</span>
              </div>
              <img class="track-img-small" src="{{$room_track->img_url}}" alt="">
              <span class="track-id" style="display: none">{{$room_track->id}}</span>
              <span class="track-title">{{$room_track->title}}</span>
            </li>
          @endforeach
        </ul>
      </div>
      {{-- track画像 --}}
      <div class="media-img-wrapper">
        <img id="media-img" class="media-img" src="{{$room_tracks[0]->img_url}}" alt="room-image">
      </div>

      {{-- trackの再生プレイヤー --}}
      <div class="track-controller">
        <div id="play-track-title">"{{$room_tracks[0]->title}}"</div>
        <div class="track-player-wrapper">
          <audio id="track-player" controls controlslist="nodownload">
            <source src="{{$room_tracks[0]->sound_url}}">
          </audio>
        </div>
      </div>
    </div>

    {{-- 作成済みtrack一覧 --}}
    <div class="my-tracks-wrapper">
      <div class="my-tracks">
        <ul id="my-track-list" class="my-track-list">
          <h3>My Tracks</h3>
          @foreach($my_tracks as $my_track)
            <li class="track-info">
              <img class="track-add-icon" src="{{ asset('icon/プラスマークアイコン 3.svg')}}" alt="+">
              <img class="track-img-small" src="{{$my_track->img_url}}" alt="">
              <span class="track-id" style="display: none">{{$my_track->id}}</span>
              <span class="track-title">{{$my_track->title}}</span>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  {{-- Room更新用form --}}
  <form id="update-form" method="POST" action=""></form>

  {{-- ボタン --}}
  <div class="buttons">
    <button><a href="javascript:history.back()">戻る</a></button>
    <button id="update-button">更新</button>
    <button>公開</button>
  </div>
  <script src="{{ asset('js/view_room.js') }}"></script>
</body>
</html>