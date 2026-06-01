@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-list">

    {{-- タイトル --}}
    <div class="attendance-list__title">

        <div class="attendance-list__line"></div>

        <h1>
            {{ $user->name }}さんの勤怠
        </h1>

    </div>

    {{-- 月移動 --}}
    <div class="attendance-list__month">

        {{-- 前月 --}}
        <a
            href="{{ route('admin.staff.attendance', ['id' => $user->id, 'month' => $prevMonth]) }}"
            class="month-link">

            ← 前月

        </a>

        {{-- 真ん中 --}}
        <div class="attendance-list__month-center">

            <img
                src="{{ asset('images/calendar.png') }}"
                alt="calendar">

            <p>
                {{ str_replace('-', '/', $month) }}
            </p>

        </div>

        {{-- 翌月 --}}
        <a
            href="{{ route('admin.staff.attendance', ['id' => $user->id, 'month' => $nextMonth]) }}"
            class="month-link">

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

        @php

        // 休憩合計
        $breakMinutes = 0;

        foreach ($attendance->breaks as $break) {

        if ($break->start_time && $break->end_time) {

        $start = \Carbon\Carbon::parse($break->start_time);
        $end = \Carbon\Carbon::parse($break->end_time);

        $breakMinutes += $end->diffInMinutes($start);
        }
        }

        // 勤務合計
        $workTime = '--';

        if ($attendance->start_time && $attendance->end_time) {

        $start = \Carbon\Carbon::parse($attendance->start_time);
        $end = \Carbon\Carbon::parse($attendance->end_time);

        $totalMinutes = $end->diffInMinutes($start) - $breakMinutes;

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        $workTime = sprintf('%d:%02d', $hours, $minutes);
        }

        @endphp

        <tr>

            {{-- 日付 --}}
            <td>
                {{ \Carbon\Carbon::parse($attendance->date)->format('m/d(D)') }}
            </td>

            {{-- 出勤 --}}
            <td>
                {{ $attendance->start_time
                    ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i')
                    : '' }}
            </td>

            {{-- 退勤 --}}
            <td>
                {{ $attendance->end_time
                    ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i')
                    : '' }}
            </td>

            {{-- 休憩 --}}
            @foreach ($attendance->breaks as $index => $break)

        <tr>

            <th>
                {{ $index == 0 ? '休憩' : '休憩' . ($index + 1) }}
            </th>

            <td colspan="3">

                <div class="attendance-detail__time">

                    <span>
                        {{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }}
                    </span>

                    <span class="attendance-detail__wave">
                        〜
                    </span>

                    <span>
                        {{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}
                    </span>

                </div>

            </td>

        </tr>

        @endforeach

        {{-- 合計 --}}
        <td>
            {{ $workTime }}
        </td>

        {{-- 詳細 --}}
        <td>

            <a href="{{ route('attendance.show', $attendance->id) }}">
                詳細
            </a>

        </td>

        </tr>

        @endforeach

    </table>
    <div class="csv-button">

        <a
            href="{{ route('admin.attendance.csv', ['id' => $user->id, 'month' => $month]) }}">

            CSV出力

        </a>

    </div>
</div>

@endsection