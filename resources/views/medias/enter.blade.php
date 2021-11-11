<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/medias.enter.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>mediaEnter</title>
</head>
<body>

  {{-- 初回の再生確認用ウィンドウ 再生アイコンをクリックされたら消える --}}
  <div id="first-play-confirm-wrapper">
    <div id="first-play-confirm">
      <p style="margin-bottom: 5px">再生を開始してもよろしいですか？</p>
      <p style="margin: 0 0 15px 0">よろしければ下の▶アイコンをクリックして下さい。</p>
      <p style="margin: 0 0 15px 0">※音量にご注意下さい。</p>
      <img id="start-play-img" class="confirm-contents" src="{{ asset('icon/play_track.svg')}}" alt="">
    </div>
  </div>

  {{-- 戻るリンク --}}
  <a href="javascript:history.back()">戻る</a>

  {{-- mediaタイトル --}}
  <div class="media-title-wrapper">
    <p id="media-id" style="display: none">{{$media_id}}</p>
    <p id="media-title">{{$media_title}}</p>
  </div>
  <div class="media-and-track-wrapper">
    {{-- media情報表示 --}}
    <div class="media-wrapper">
      {{-- trackリスト --}}
      <div class="media-tracks-wrapper">
        <div class="media-track-list-cover"></div>
        <ul id="media-track-list" class="media-track-list">
          @foreach($media_tracks as $index => $media_track)
            <li class="track-info">
              <div class="track-info-cover"></div>
              <div class="icon-and-index">
                <span class="track-index">{{$index+1}}</span>
              </div>
              <img class="track-img-small" src="{{$media_track->img_url}}" alt="">
              <span class="track-id" style="display: none">{{$media_track->id}}</span>
              <span class="track-title">{{$media_track->title}}</span>
              <span class="track-sound-url">{{$media_track->sound_url}}</span>
            </li>
          @endforeach
        </ul>
      </div>
      {{-- track画像 --}}
      <div class="media-img-wrapper">
        <img id="media-img" class="media-img" src="{{$media_tracks[0]->img_url}}" alt="media-image">
      </div>

      {{-- 再生中のトラックタイトル --}}
      <div id="playing-track-title-wrapper">
        <h3 id="playing-track-title">{{$media_tracks[0]->title}}</h3>
      </div>

      {{-- 再生制御用 --}}
      <div class="vol-control-wrapper">
        <img id="volume-icon" class="player-control-icon"src="{{ asset('icon/volume.svg')}}" alt="">
        <img id="mute-icon" class="player-control-icon" src="{{ asset('icon/mute.svg')}}" alt="">
        <img id="previous-track-icon" class="player-control-icon" src="{{ asset('icon/previous_track.svg')}}" alt="">
        <img id="next-track-icon" class="player-control-icon" src="{{ asset('icon/next_track.svg')}}" alt="">
      </div>

      {{-- trackの再生プレイヤー --}}
      {{-- <div class="track-controller">
        <div id="play-track-title">"{{$media_tracks[0]->title}}"</div>
        <div class="track-player-wrapper">
          <audio id="track-player" controls controlslist="nodownload">
            <source src="{{$media_tracks[0]->sound_url}}">
          </audio>
        </div>
      </div> --}}

    </div>

  <script src="{{ asset('js/enter_media.js') }}"></script>
</body>
</html>