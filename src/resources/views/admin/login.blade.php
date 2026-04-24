@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<h1>管理者ログイン</h1>

<form method="POST" action="{{ route('admin.login.post') }}">
    @csrf

    <div>
        <label>メールアドレス</label><br>
        <input type="email" name="email">
        @error('email')
        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label>パスワード</label><br>
        <input type="password" name="password">
    </div>

    <br>

    <button type="submit">
        ログインする
    </button>
</form>

@endsection