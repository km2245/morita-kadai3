@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<div class="login">

    <h1 class="login__title">ログイン</h1>

    <form method="POST" action="/login" novalidate>
        @csrf

        {{-- メールアドレス --}}
        <div class="login__group">
            <label>メールアドレス</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}">

            <p class="error-message">
                @error('email')
                {{ $message }}
                @enderror
            </p>
        </div>

        {{-- パスワード --}}
        <div class="login__group">
            <label>パスワード</label>

            <input
                type="password"
                name="password">

            <p class="error-message">
                @error('password')
                {{ $message }}
                @enderror
            </p>
        </div>

        {{-- ボタン --}}
        <button class="login__button" type="submit">
            ログインする
        </button>

    </form>

    {{-- 会員登録リンク --}}
    <div class="login__register">
        <a href="/register">
            会員登録はこちら
        </a>
    </div>

</div>

@endsection