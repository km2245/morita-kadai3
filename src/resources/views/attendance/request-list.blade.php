@extends('layouts.app')

@section('content')

<h1>申請一覧</h1>
<div>
    <a href="{{ route('attendance.request.list', ['tab' => 'pending']) }}">
        承認待ち
    </a>

    |

    <a href="{{ route('attendance.request.list', ['tab' => 'approved']) }}">
        承認済み
    </a>
</div>

<br>
<table border="1">
    <tr>
        <th>日付</th>
        <th>理由</th>
        <th>ステータス</th>
    </tr>

    @foreach ($requests as $request)
    <tr>
        <td>{{ optional($request->attendance)->date }}</td>
        <td>{{ $request->reason }}</td>
        <td>{{ $request->status }}</td>
    </tr>
    @endforeach

</table>

@endsection