<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('css/searchresult.css')}}">
  <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script> {{-- jquery --}}

  <title>Document</title>
</head>
<body>


  <div id="app">
    {{-- ヘッダー --}}
    <header-component
    :csrf="{{json_encode(csrf_token())}}"
    :is-login=@json($isLogin)>
    </header-component>
    
    {{-- 検索結果一覧 --}}
    <section class="search-result">
      <h2 class="search-result-message">"{{$keyword}}"の検索結果</h2>
  
      <media-preview-component
      :csrf="{{json_encode(csrf_token())}}"
      :media-preview-infos='@json($mediaPreviewInfos,JSON_UNESCAPED_SLASHES)'
      :is-show-cover="false">
      </media-preview-component>
    </section>
  
  </div>

  <script src="{{ mix('/js/app.js') }}"></script>


</body>
</html>