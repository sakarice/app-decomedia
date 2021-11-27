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
    {{-- <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet"> --}}

    <title>Decomedia</title>

</head>
<body>
    <section id="home-top">
        {{-- タイトルコピー --}}
        <div class="top-message-wrapper">
            <h2 class="top-message">
                <span>創作に手が出せなかった</span><br>
                <span>あなたへ</span>
            </h2>
            <p class="sub-message">
                0からコンテンツを作るのではなく、<br>
                既にある画像や音楽、動画を組み合わせて<br>
                メディアとして公開できるサービスです<br><br>
                アイデアを形にするためのスキル・時間の<br>
                ハードルを少しでも下げ、<br>
                創作を楽しむきっかけを提供します
            </p>
        </div>

        {{-- 検索ウィンドウとマイページへのリンク --}}
        <div class="search-wrapper">
            {{-- 検索フォーム --}}
            <div class="search-form-wrapper">
                <form method="POST" class="search-form" action="/media/show/search/result">
                    @csrf
                    <input class="search-input" type="text" name="keyword" size="30" placeholder="検索ワード">
                    <button type="submit" class="search-icon-wrapper">
                        <i class="fas fa-search search-icon"></i>
                    </button>
                </form>
            </div>
            <p class="sample-keywords">カフェ、雨、勉強、作業用、ロック、自然、chill、etc...</p>
        </div>
        {{-- マイページへのリンク --}}
        <div class="link-wrapper">
            <span class="link-message">自分のメディアを作成する。</span>
            <a class="link-to-mypage" href="/mypage">マイページへ</a>
            {{-- <button class="link-to-mypage-button"></button> --}}
        </div>
    </section>

    <section id="quick-use-and-warning">
        <p class="use-and-warning-message">
            <span style="color:red">
                ※メディアに移動すると音楽・動画が再生されます。<br>
            </span>
            イヤホンやヘッドフォンを付け、<br>
            音量に注意してお楽しみください。
        </p>
    </section>

    <section id="about-app"></section>

    <div id="app">
        {{-- ヘッダー --}}
        <header-component
        :csrf="{{json_encode(csrf_token())}}">
        </header-component>

        {{-- Mediaの一覧を表示 --}}
        <section class="recently-posted-medias">
            <h3 class="section-title recently-posted-title">最近の投稿</h3>
            <span class="recently-posted-supplement-info">
                (サムネイルをタップすると再生画面に移動します)
            </span>
            <div class="media-preview-wrapper">
                <media-preview-component
                :csrf="{{json_encode(csrf_token())}}"
                :media-preview-infos='@json($mediaPreviewInfos,JSON_UNESCAPED_SLASHES)'
                :is-show-cover="false">
                </media-preview-component>
            </div>
        </section>

    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
     
</body>
</html>
