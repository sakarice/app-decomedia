<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('/css/defaultFile.css')}}">

  <title>Document</title>
</head>
<body>
  <section id="upload">
    <form id="upload-form" action="uploadDefaultFiles" method="POST" enctype="multipart/form-data">

      <div class="upload-wrapper">
        <div class="img-upload-wrapper">
          <div class="img-file-upload-wrapper">
            {{csrf_field()}}
            <h2>デフォルト画像をアップロード</h2>
            <input form="upload-form" class="image-upload" type="file" accept="image/*" name="img">
          </div>
        </div>
  
        <div class="audio-upload-wrapper">
          <div class="audio-file-upload-wrapper">
            {{csrf_field()}}
            <h2>デフォルトBGMをアップロード</h2>
            <input form="upload-form" class="audio-upload"  type="file" accept="audio/*" name="audio">
          </div>
          <div class="audio-thumbnail-upload-wrapper">
            {{csrf_field()}}
            <h2>デフォルトBGMのサムネイル画像をアップロード</h2>
            <input form="upload-form" class="audio-thumbnail-upload"  type="file" accept="image/*" name="audio-thumbnail">
          </div>
        </div>
      </div>

      <div class="button-wrapper">
        <button class="submit-btn" type="submit">アップロード</button>
      </div>
    </form>
  </section>

</body>
</html>