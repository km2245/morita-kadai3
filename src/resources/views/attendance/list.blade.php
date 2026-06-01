@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-list">

    {{-- タイトル --}}
    <div class="attendance-list__title">
        <span class="attendance-list__line"></span>
        <h1>勤怠一覧</h1>
    </div>

    {{-- 月移動 --}}
    <div class="attendance-list__month">

        <a class="month-link" href="{{ route('attendance.index', [
            'month' => \Carbon\Carbon::parse($month . '-01')->subMonth()->format('Y-m')
        ]) }}">
            ← 前月
        </a>

        <div class="attendance-list__month-center">
            <img src="{{ asset('images/calendar.png') }}" alt="calendar">
            <p>{{ str_replace('-', '/', $month) }}</p>
        </div>

        <a class="month-link" href="{{ route('attendance.index', [
            'month' => \Carbon\Carbon::parse($month . '-01')->addMonth()->format('Y-m')
        ]) }}">
            翌月 →
        </a>

    </div>

    {{-- テーブル --}}
    <table class="attendance-table">
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>

        @foreach ($attendances as $attendance)
        <tr>

            <td>
                {{ \Carbon\Carbon::parse($attendance->date)->format('m/d') }}
                （{{ ['日','月','火','水','木','金','土'][\Carbon\Carbon::parse($attendance->date)->dayOfWeek] }}）
            </td>

            <td>
                {{ $attendance->start_time
                ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i')
                : '' }}
            </td>

            <td>
                {{ $attendance->end_time
                ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i')
                : '' }}
            </td>

            <td>
                {{ $attendance->break_total }}
            </td>

            <td>
                {{ $attendance->work_total }}
            </td>

            <td>
                <a href="{{ route('attendance.show', $attendance->id) }}">
                    詳細
                </a>
            </td>

        </tr>
        @endforeach
    </table>

</div>

@endsection