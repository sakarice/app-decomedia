@extends('layouts.app')

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
<div class="toMypage" style="margin: 50px 0; font-size: 30px">
    {{-- <a href="http://localhost:8000/home/mypage">マイページへ</a> --}}
    <a href="home/mypage">マイページへ</a>
</div>
