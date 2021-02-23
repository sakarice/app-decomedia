<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('/css/createtrack.css')}}">
  <title>Document</title>
</head>
<body>
  <a href="../mypage" style="font-size: 30px">←マイページへ</a>
  <div style="font-size: 40px; text-align: center"><h1 style="margin: 0 0 30px 0">Track作成</h1></div>
  <div class="upload_wrapper">

    <form method="POST" action="createtrack" enctype="multipart/form-data">
      {{-- 画像アップロードフォーム --}}
      <div class="img_upload_wrapper">
        <div class="upload-title">サムネイル</div>
        <div id="drop-1" class="drop-zone">
          <div>画像ファイル<br>ドラッグ&ドロップ</div>
        </div>
        {{csrf_field()}}
        <div class="input-form">
          <input id="imgfile"  class="form-control" type="file" accept="image/*" name="img">
        </div>
      </div>

      {{-- BGMアップロードフォーム --}}
      <div class="sound_upload_wrapper">
        <div class="upload-title">BGM</div>
        <div id="drop-2" class="drop-zone">
          <div>音声ファイル<br>ドラッグ&ドロップ</div>
        </div>
        {{csrf_field()}}
        <div class="input-form">
          <input id="soundfile"  class="form-control" type="file" accept="audio/*" name="audio">
        </div>
      </div>

      {{-- 送信ボタン --}}
      <button class="submitButton" type="submit">Track作成</button>
    </form>  
  </div>

  <script src="{{ asset('/js/createtrack.js')}}"></script>
</body>
</html>