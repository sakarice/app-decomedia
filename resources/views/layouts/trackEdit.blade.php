<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {{-- FontAwesome CDN --}}
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

  {{-- original css --}}
  <link rel="stylesheet" href=@yield('css_link')>

  <title>Document</title>
</head>
<body>
  <a href="../mypage" style="font-size: 30px">←マイページへ</a>
  <div style="font-size: 40px; text-align: center">
    <h1 style="margin: 0 0 30px 0">
    Track @yield('createOrUpdate')
    </h1>
  </div>

  @yield('msg')
  {{-- {{$msg}} --}}

  <form class="upload-form" method="POST" action="createtrack" enctype="multipart/form-data">
    <div class="upload-wrapper">
      {{-- アップロード：ドラッグ・ドロップ --}}
      {{-- サムネイル表示 --}}
      <div id="preview_wrapper" class="preview_wrapper">
        <div id="drop-zone" class="drop-zone">
          <div>画像or音声ファイル<br>ドラッグ&ドロップ</div>
        </div>
        <div id="sound-preview">
          <form action="" id="sound-title-form">
            <p id="sound-title">
              <i class="fas fa-music"></i><span>Trackタイトル</span>
              <input id="sound-title-in" form="sound-title-form">
              <input type="hidden" id="sound-title-in-hidden" name="sound-title-in-hidden">
            </p>
          </form>
        </div>
      </div>

      {{-- アップロード：フォルダから選択 --}}
      <div class="button-zone">
        {{-- 画像アップロードフォーム --}}
        <div class="img-upload-wrapper">
          {{csrf_field()}}
          <div class="input-form">
            <div style="text-align: left">
              <i class="fas fa-image fa-2x" style="margin-right: 15px"></i>
              <span>背景画像</span>
            </div>
            <input id="imgfile"  class="form-control" type="file" accept="image/*" name="img">
          </div>
        </div>
  
        {{-- BGMアップロードフォーム --}}
        <div class="sound-upload-wrapper">
          {{csrf_field()}}
          <div class="input-form">
            <div style="text-align: left">
              <i class="fas fa-compact-disc fa-2x" style="margin-right: 15px"></i>
              <span>BGM</span>
            </div>
              <input id="soundfile"  class="form-control" type="file" accept="audio/*" name="audio">
          </div>
        </div>

        {{-- 送信ボタン --}}
        <div class="submit-wrapper">
          <button class="submitButton" type="submit">
            Track @yield('createOrUpdate')
          </button>
        </div>
      </div>
    </div>
    
  </form>

  {{-- javascript source link --}}
  <script src=@yield('javascriptLink')></script>

</body>
</html>