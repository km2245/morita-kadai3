@extends('layouts.app')

@section('content')

<h1>{{ $user->name }} さんの勤怠一覧</h1>

<table border="1">
    <tr>
        <th>日付</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>詳細</th>
    </tr>

    @foreach ($attendances as $attendance)
    <tr>
        <td>{{ $attendance->date }}</td>
        <td>{{ $attendance->start_time }}</td>
        <td>{{ $attendance->end_time }}</td>
        <td>
            <a href="{{ route('attendance.show', $attendance->id) }}">
                詳細
            </a>
        </td>
    </tr>
    @endforeach

</table>

@endsection