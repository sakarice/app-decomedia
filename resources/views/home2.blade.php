{{-- @extends('layouts.app') --}}

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/home.view.css') }}">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>Home</title>
</head>
<body>
    <div class="home-top">

        {{-- タイトルコピー --}}
        <div class="top-message-wrapper">
            <h2 class="top-message">Find your place</h2>
            <p style="text-align: center">音楽×画像×動画=Room。今日はどのRoomで過ごそう。</p>
        </div>

        {{-- 検索ウィンドウとマイページへのリンク --}}
        <div class="search-wrapper">
            {{-- 検索フォーム --}}
            <div class="search-window">
                <form method="POST" action="home/searchResult">
                    @csrf
                    <input class="search-input" type="text" name="keyword" size="30" placeholder="検索ワード">
                    <i class="fas fa-search search-icon"></i>
                </form>
            </div>
            <p class="sample-keywords">カフェ、雨、勉強、作業用、ロック、自然、chill、etc...</p>
        </div>
        {{-- マイページへのリンク --}}
        <div class="link-wrapper">
            <span class="link-message">自分のRoomを作成する。</span>
            <a class="link-to-mypage" href="home/mypage">ログイン</a>
            {{-- <button class="link-to-mypage-button"></button> --}}
        </div>

    </div>



    <div id="app">
        {{-- ヘッダー --}}
        <header-component
        :is-show-login="true"
        :is-show-signup="true"
        :is-show-profile-icon="false">
        </header-component>

        {{-- Roomの一覧を表示 --}}
        <section class="recently-posted-rooms">
            <h3 class="section-title">最近の投稿</h3>
            <div class="room-list-wrapper">
                <room-list-component
                :room-preview-infos=@json($roomPreviewInfos)
                :is-show-cover="false">
                </room-list-component>
            </div>
        </section>
    </div>
 

    {{-- @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection --}}

{{-- <script src="{{ asset('js/search_result.js') }}"></script> --}}
<script src="{{ mix('/js/app.js') }}"></script>

    
</body>
</html>