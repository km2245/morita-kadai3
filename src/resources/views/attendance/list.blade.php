@extends('layouts.app')

@section('content')
@extends('layouts.app')

@section('content')

<h1>勤怠一覧</h1>

<div>
    {{-- 前月 --}}
    <a href="{{ route('attendance.index', [
        'month' => \Carbon\Carbon::parse($month . '-01')->subMonth()->format('Y-m')
    ]) }}">
        ← 前月
    </a>

    <span>{{ $month }}</span>

    {{-- 翌月 --}}
    <a href="{{ route('attendance.index', [
        'month' => \Carbon\Carbon::parse($month . '-01')->addMonth()->format('Y-m')
    ]) }}">
        翌月 →
    </a>
</div>

<table border="1">
    <tr>
        <th>日付</th>
        <th>出勤時間</th>
        <th>退勤時間</th>
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