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
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>Home</title>
</head>
<body>
    <section id="home-top">
        {{-- タイトルコピー --}}
        <div class="top-message-wrapper">
            <h2 class="top-message">聴いて、観て、繋がる</h2>
            <p class="sub-message">
                好みの音楽や動画で構成されたRoomを通して<br>
                同じ感性の人々と繋がりましょう
            </p>
        </div>

        {{-- 検索ウィンドウとマイページへのリンク --}}
        <div class="search-wrapper">
            {{-- 検索フォーム --}}
            <div class="search-form-wrapper">
                <form method="POST" class="search-form" action="/room/show/search/result">
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
            <span class="link-message">自分のRoomを作成する。</span>
            <a class="link-to-mypage" href="/mypage">マイページへ</a>
            {{-- <button class="link-to-mypage-button"></button> --}}
        </div>
    </section>

    <section id="quick-use-and-warning">
        <p class="use-and-warning-message">
            <span style="color:red">
                ※ルームに移動すると音楽・動画が再生されます。<br>
            </span>
            イヤホンやヘッドフォンを付け、<br>
            音量に注意してお楽しみください。
        </p>
    </section>
    <section id="about-app">
        {{-- <h2 id="about-app-header">
            Roomとは?
        </h2>
        <p class="about-app-description">
            音楽、動画、画像を組み合わせて作る疑似空間です。<br>
            誰かの作ったRoomを視聴したり、<br>
            オリジナルのRoomを作って公開することができます。<br>
            お気に入りの動画にBGMを付け加えたり、<br>
            音楽に背景を付けて簡易MVにしたり、<br>
            楽しみ方はあなた次第です。
        </p> --}}
        {{-- <h2 id="how-to-use-header">
            使い方
        </h2>
        <div class="about-app-wrapper">
            <div class="about-app about-watch left">
                <h3 class="about-app-title">視聴</h3>
                <p class="watch-description">多種多様なRoomから好みのRoomを見つけて視聴してみましょう</p>
                <img class="about-app-img" src="https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/app/img/how-to-use-app_watch.png" alt="">
            </div>
            <div class="about-app about-create right">
                <h3 class="about-app-title">作成</h3>
                <p class="create-description">音楽、動画、画像を組み合わせ、好みの空間を作りましょう。</p>
                <img class="about-app-img" src="https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/app/img/how-to-use-app_create.png" alt="">
            </div>
        </div> --}}
    </section>

    <div id="app">
        {{-- ヘッダー --}}
        <header-component
        :csrf="{{json_encode(csrf_token())}}"
        :is-login=@json($isLogin)>
        {{-- :is-show-profile-icon="false" --}}
        </header-component>

        {{-- Roomの一覧を表示 --}}
        <section class="recently-posted-rooms">
            <h3 class="section-title recently-posted-title">最近の投稿</h3>
            <span class="recently-posted-supplement-info">
                (サムネイルをタップするとルームに移動します)
            </span>
            <div class="room-preview-wrapper">
                <room-preview-component
                :csrf="{{json_encode(csrf_token())}}"
                :room-preview-infos='@json($roomPreviewInfos,JSON_UNESCAPED_SLASHES)'
                :is-show-cover="false">
                </room-preview-component>
            </div>
        </section>
    </div>
 

<script src="{{ mix('/js/app.js') }}"></script>

    
</body>
</html>
