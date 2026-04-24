@extends('layouts.app')

@section('content')

<h1>管理者 勤怠一覧</h1>

<table border="1">
    <tr>
        <th>名前</th>
        <th>メール</th>
        <th>勤怠一覧</th>
    </tr>

    @foreach ($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            <a href="#">
                詳細
            </a>
        </td>
    </tr>
    @endforeach

</table>

@endsection