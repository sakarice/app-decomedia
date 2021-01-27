<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('css/tracks.view.css') }}">
  <title>tracks</title>
</head>
<body>
  {{-- 作成済みトラック一覧 --}}
  <a href="../mypage" style="font-size: 30px">←マイページへ戻る</a>
  <div class="track-show-wrapper">
    <h3 class="preview-track-title">作成済みトラック</h3>
    <form method="POST" action="tracks">
      <button class="delete-button" type="submit">削除</button>
      @foreach($trackDatas as $trackData)
      @csrf
      <div class="preview-track">
        <input class="track-select" type="checkbox" name="selected-track[]" value={{$trackData['id']}}>
        <img class="preview-img" src={{$trackData['url']}} alt="">
        {{$trackData['title']}}
      </div>
      @endforeach
      {{-- {{$ids}} --}}
    </form>
  </div>
</body>
</html>