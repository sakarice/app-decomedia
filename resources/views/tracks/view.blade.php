<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta id="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/tracks.view.css') }}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>tracks</title>
</head>
<body>
  {{-- 作成済みトラック一覧 --}}
  <a href="../mypage" style="font-size: 30px">←マイページへ戻る</a>
  <div class="track-show-wrapper">
    <h3 class="preview-track-title">作成済みトラック</h3>
    <div class="button-wrapper">
      <a href="createtrack" style="text-decoration: none; padding: 0;">
        <input class="button create-button" type="submit" value="Track作成">
      </a>
      <input class="button delete-button" type="submit" id="delete-button" value="Track削除" form="select-form">
      <div class="create-room">
        <input class="button room-create-button" type="submit" id="room-create-button" value="Room作成">
        <input class="input-room-title" type="text" name="room-title" id="room-title" placeholder="room名">
      </div>
    </div>
    <form method="POST" action="tracks" id="select-form" name="form">
      {{-- トラックの一覧↓↓↓ --}}
      @foreach($trackDatas as $trackData)
        @csrf
        <div class="preview-track">
          <p class="track_id_disp">{{$trackData['id']}}</p>
          <p id="selected-seq-{{$trackData['id']}}" class="selected-seq"></p>
          <input class="track-select" type="checkbox" name="selected-track-ids[]" value={{$trackData['id']}} onchange="checkState(this);">
          <a id={{$trackData['id']}} onclick="submitUpdateTrack(this.id)" class="link-to-update-track">
            <img class="preview-img" src={{$trackData['url']}} alt="">
            {{$trackData['title']}}
          </a>
        </div>
      @endforeach
    </form>
    {{-- トラックの一覧↑↑↑ --}}

    {{-- トラック更新用フォーム --}}
    <form method="POST" action="updatetrack" id="update-form">
      @csrf
      <input id="update-input" type="hidden" name="track_id">
    </form>

    <script src="{{ asset('js/tracks.js') }}"></script>
    {{-- {{$ids}} --}}
  </div>
</body>
</html>