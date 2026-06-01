@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-list">

    {{-- タイトル --}}
    <div class="attendance-list__title">

        <div class="attendance-list__line"></div>

        <h1>
            {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠
        </h1>

    </div>

    <div class="attendance-list__month">

        {{-- 前日 --}}
        <a
            href="{{ route('admin.attendance.list', ['date' => $prevDate]) }}"
            class="month-link">
            ← 前日
        </a>

        {{-- 日付 --}}
        <div class="attendance-list__month-center">

            <img
                src="{{ asset('images/calendar.png') }}"
                alt="calendar">

            <p>
                {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}
            </p>

        </div>

        {{-- 翌日 --}}
        <a
            href="{{ route('admin.attendance.list', ['date' => $nextDate]) }}"
            class="month-link">
            翌日 →
        </a>

    </div>

    {{-- テーブル --}}
    <table class="attendance-table">

        <tr>
            <th>名前</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>

        @foreach ($attendances as $attendance)

        <tr>

            <td>
                {{ optional($attendance->user)->name }}
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
                --
            </td>

            <td>
                --
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