@extends('layouts.app')

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
    {{-- マイページへのリンク --}}
    <div class="toMypage" style="margin: 50px 0; font-size: 30px">
        <a href="/mypage">マイページへ</a>
    </div>

    {{-- 検索フォーム --}}
    <div class="search-window">
        <form method="POST" action="/media/show/search/result">
            @csrf
            <input type="text" name="keyword" placeholder="検索ワード">
        </form>
    </div>


@section('content')
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
@endsection


    
</body>
</html>
