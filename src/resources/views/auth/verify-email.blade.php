@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')

<div class="verify">

    <p class="verify__text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a href="/" class="verify__button">
        認証はこちらから
    </a>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button class="verify__resend">
            認証メールを再送する
        </button>
    </form>

</div>

@endsection