@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<div class="login">

    {{-- タイトル --}}
    <div class="login-form__heading">
        <h1 class="login__title">
            管理者ログイン
        </h1>
    </div>

    {{-- フォーム --}}
    <form
        class="form"
        action="{{ route('admin.login') }}"
        method="post" novalidate>

        @csrf

        {{-- メール --}}
        <div class="form__group">

            <label class="form__label">
                メールアドレス
            </label>

            <input
                class="form__input"
                type="email"
                name="email"
                value="{{ old('email') }}">

            @error('email')
            <p class="error-message">
                {{ $message }}
            </p>
            @enderror

        </div>

        {{-- パスワード --}}
        <div class="form__group">

            <label class="form__label">
                パスワード
            </label>

            <input
                class="form__input"
                type="password"
                name="password">

            @error('password')
            <p class="error-message">
                {{ $message }}
            </p>
            @enderror

        </div>

        {{-- ボタン --}}
        <button
            class="login__button"
            type="submit">

            管理者ログインする

        </button>

    </form>

</div>

@endsection