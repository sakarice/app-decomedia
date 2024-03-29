<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta id="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/mypage.view.css') }}">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}
  <title>UserPage</title>
</head>
<body>
  {{-- <div class="header-wrapper"></div><br> --}}

  <div class="contents-wrapper">

    <div id="app">
      {{-- ヘッダー --}}
      <header-component
      :csrf="{{json_encode(csrf_token())}}"
      :is-login="true">
      </header-component>

      <section style="height:100px"></section>

      <mypage-component
        :created-media-preview-infos-from-parent='@json($createdMediaPreviewInfos,JSON_UNESCAPED_SLASHES)'
        :liked-media-preview-infos-from-parent='@json($likedMediaPreviewInfos,JSON_UNESCAPED_SLASHES)'>
      </mypage-component>

    </div>

  </div>
  <script src="{{ asset('js/mypage.js') }}"></script>
  <script src="{{ mix('/js/app.js') }}"></script>

</body>
</html>