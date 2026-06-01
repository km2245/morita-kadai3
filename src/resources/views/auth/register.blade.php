@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')

<div class="register">

    <h1 class="register__title">会員登録</h1>

    <form action="/register" method="post" novalidate>
        @csrf

        {{-- 名前 --}}
        <div class="register__group">
            <label>名前</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}">

            <p class="register__error">
                @error('name')
                {{ $message }}
                @enderror
            </p>
        </div>

        {{-- メールアドレス --}}
        <div class="register__group">
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
        <div class="register__group">
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

        {{-- パスワード確認 --}}
        <div class="register__group">
            <label>パスワード確認</label>

            <input
                type="password"
                name="password_confirmation">
        </div>

        {{-- ボタン --}}
        <button class="register__button" type="submit">
            登録する
        </button>

    </form>

    {{-- ログインリンク --}}
    <div class="register__login">
        <a href="/login">
            ログインはこちら
        </a>
    </div>

</div>

@endsection