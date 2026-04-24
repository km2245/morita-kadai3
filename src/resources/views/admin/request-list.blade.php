@extends('layouts.app')

@section('content')

<h1>管理者 申請一覧</h1>

<table border="1">
    <tr>
        <th>名前</th>
        <th>日付</th>
        <th>理由</th>
        <th>ステータス</th>
        <th>詳細</th>
    </tr>

    @foreach ($requests as $request)
    <tr>

        {{-- 申請したユーザー名 --}}
        <td>{{ optional($request->user)->name }}</td>

        {{-- 対象の勤怠日 --}}
        <td>{{ optional($request->attendance)->date }}</td>

        {{-- 修正理由 --}}
        <td>{{ $request->reason }}</td>

        {{-- 承認状態 --}}
        <td>{{ $request->status }}</td>

        {{-- 詳細画面（次で作る） --}}
        <td>
            <a href="{{ route('admin.request.approve', $request->id) }}">
                詳細
            </a>
        </td>

    </tr>
    @endforeach

</table>

@endsection