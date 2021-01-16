<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('/css/maketrack.css')}}">
  <title>Document</title>
</head>
<body>
  <a href="../mypage" style="font-size: 30px">←マイページへ</a>
  <div class="upload_wrapper">
    <p style="margin-top: 30px;">サムネイル</p>
    <div id="preview_wrapper" class="preview_wrapper"></div>
    {{-- <img id="preview" src="data:image/base64;R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="画像を選択して下さい"> --}}
    <form method="POST" action="maketrack" enctype="multipart/form-data">
      <div class="img_upload_wrapper">
        {{csrf_field()}}
        【サムネイル画像】<input id="imgfile"  class="form-control" type="file" accept="image/*" name="img" onchange="previewImage(this);">
      </div>
      <div class="sound_upload_wrapper">
        {{csrf_field()}}
        【BGM】<input id="soundfile"  class="form-control" type="file" accept="audio/*" name="audio">
      </div>
      <button class="submitButton" type="submit">Track作成</button>
    </form>  
  </div>

  <script src="{{ asset('/js/maketrack.js')}}"></script>
</body>
</html>