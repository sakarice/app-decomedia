<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('/css/publicFile.css')}}">

  <title>Document</title>
</head>
<body>
  <section id="upload">
    <form id="upload-form" action="/uploadPublicFiles" method="POST" enctype="multipart/form-data">

      <h2 class="upload-title">アップロードフォーム</h2>
      <div class="upload-area">
        {{-- <div class="img-upload-wrapper"></div> --}}
        <div class="img-file-upload-wrapper upload-wrapper">
          {{csrf_field()}}
          <h3 class="sub-title">デフォルト画像</h3>
          <input form="upload-form" class="upload" type="file" accept="image/*" name="img">
        </div>
  
        {{-- <div class="audio-upload-wrapper upload-wrapper"></div> --}}
        <div class="audio-file-upload-wrapper upload-wrapper">
          {{csrf_field()}}
          <h3 class="sub-title">オーディオファイル</h3>
          <span class="sub-describe">※↓のサムネをアップロードしない場合はデフォルトの♬(音符)サムネが割り当てられます</span>
          <input form="upload-form" class="upload"  type="file" accept="audio/*" name="audio">
        </div>
        <div class="audio-thumbnail-upload-wrapper upload-wrapper">
          {{csrf_field()}}
          <h3 class="sub-title">オーディオのサムネイル画像</h3>
          <span class="sub-describe">※サムネだけのアップロードはできません。</span>
          <input form="upload-form" class="upload"  type="file" accept="image/*" name="audio-thumbnail">
        </div>
      </div>

      <div class="button-wrapper">
        <button class="submit-btn" type="submit">アップロード</button>
      </div>
    </form>
  </section>

</body>
</html>