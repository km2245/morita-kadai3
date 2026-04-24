@extends('layouts.app')

@section('content')

<h1>修正申請 承認画面</h1>

<p>名前：{{ optional($request->user)->name }}</p>

<p>日付：{{ optional($request->attendance)->date }}</p>

<p>理由：{{ $request->reason }}</p>

<p>ステータス：{{ $request->status }}</p>

<form method="POST" action="{{ route('admin.request.approve.post', $request->id) }}">
    @csrf

    <button type="submit">
        承認する
    </button>
</form>

@endsection