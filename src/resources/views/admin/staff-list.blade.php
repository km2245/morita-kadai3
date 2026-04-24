@extends('layouts.app')

@section('content')

<h1>スタッフ一覧</h1>

<table border="1">
    <tr>
        <th>名前</th>
        <th>メールアドレス</th>
        <th>勤怠</th>
    </tr>

    @foreach ($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            <a href="{{ route('admin.staff.attendance', $user->id) }}">
                詳細
            </a>
        </td>
    </tr>
    @endforeach

</table>

@endsection