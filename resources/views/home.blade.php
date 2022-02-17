{{-- @extends('layouts.app') --}}

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta name="viewport" content="width=480px, initial-scale=1.0"> --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/home.view.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

    <title>Decomedia</title>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-219618733-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-219618733-1');
</script>

</head>
<body>
<div id="app">

    <section id="home-top-wrapper">
        <!-- {{-- タイトルコピー --}} -->
        <div class="top-message-wrapper">
            <h2 class="top-message">
                <span>創作に手が出せなかった あなたへ</span><br>
            </h2>
            <p class="sub-message">
                画像や音楽を「選んで配置する」だけ。<br>
                3分で作れるあなただけのメディア。<br>
            </p>
        </div>

        <home-top-component 
        :csrf="{{json_encode(csrf_token())}}">
        </home-top-component>

        {{-- 検索ウィンドウとマイページへのリンク --}}
        {{-- <div class="search-wrapper"> --}}
            {{-- 検索フォーム --}}
            {{-- <div class="search-form-wrapper">
                <form method="POST" class="search-form" action="/media/show/search/result">
                    @csrf
                    <input class="search-input" type="text" name="keyword" size="30" placeholder="検索ワード">
                    <button type="submit" class="search-icon-wrapper">
                        <i class="fas fa-search search-icon"></i>
                    </button>
                </form>
            </div>
            <p class="sample-keywords">カフェ、雨、勉強、作業用、ロック、自然、chill、etc...</p> --}}
        {{-- </div> --}}
        {{-- マイページへのリンク --}}
        {{-- <div class="link-wrapper">
            <span class="link-message">自分のメディアを作成する。</span>
            <a class="link-to-mypage" href="/mypage">マイページへ</a>
            {{-- <button class="link-to-mypage-button"></button> --}}
        {{-- </div> --}} --}}
    </section>

    {{-- <section id="quick-use-and-warning">
        <p class="use-message-title" style="font-weight:bold;">
            《メディアの視聴》
        </p>
        <img class="headphone-img" src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/%E3%83%98%E3%83%83%E3%83%89%E3%83%95%E3%82%A9%E3%83%B33.png" alt="">
        <p class="use-message">
            イヤホンやヘッドフォンを付け、<br>
            音量に注意してお楽しみください。
        </p>
        <span class="warning-message">
            (メディアに移動すると音楽・動画が再生されます。)<br>
        </span>
    </section> --}}

    <section id="about-app"></section>

    {{-- <div id="app"> --}}
    {{-- ヘッダー --}}
    <header-component
    :csrf="{{json_encode(csrf_token())}}">
    </header-component>

    {{-- Mediaの一覧を表示 --}}
    <section class="recently-posted-medias">
        {{-- <p class="sub-title">Medias</p> --}}
        <h3 class="section-title recently-posted-title">
            <i class="fas fa-headphones-alt headphone-icon"></i>
            <span style="margin:0 5px">最近作られたコンテンツ(メディア)を視聴</span>
            <i class="fas fa-headphones-alt headphone-icon"></i>
        </h3><br>
        <span class="media-watch-warning">※メディアに移動すると音楽・動画が再生されます</span>
        {{-- <span class="recently-posted-supplement-info">
            (サムネイルをタップすると再生画面に移動します)
        </span> --}}
        <div class="media-preview-wrapper">
            <media-preview-component
            :csrf="{{json_encode(csrf_token())}}"
            :media-preview-infos='@json($mediaPreviewInfos,JSON_UNESCAPED_SLASHES)'
            :is-show-cover="false">
            </media-preview-component>
        </div>
    </section>

    <footer-component></footer-component>

</div>

    <script src="{{ mix('/js/app.js') }}"></script>
     
</body>
</html>
