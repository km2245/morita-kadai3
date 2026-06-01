@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-detail">

    {{-- タイトル --}}
    <div class="attendance-detail__title">
        <div class="attendance-detail__line"></div>
        <h1>勤怠詳細</h1>
    </div>

    <form
        action="{{ route('attendance.request', $attendance->id) }}"
        method="POST">

        @csrf

        <table class="attendance-detail__table">

            {{-- 名前 --}}
            <tr>
                <th>名前</th>
                <td colspan="3">
                    {{ auth()->user()->name }}
                </td>
            </tr>

            {{-- 日付 --}}
            <tr>
                <th>日付</th>

                <td>
                    {{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                </td>

                <td colspan="2">
                    {{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                </td>
            </tr>

            {{-- 出勤退勤 --}}
            <tr>
                <th>出勤・退勤</th>

                <td>
                    <input
                        type="time"
                        name="start_time"
                        value="{{ old(
                        'start_time',
                        \Carbon\Carbon::parse($attendance->start_time)->format('H:i')
                    ) }}">
                </td>

                <td class="attendance-detail__wave">〜</td>

                <td>
                    <input
                        type="time"
                        name="end_time"
                        value="{{ old(
                        'end_time',
                        \Carbon\Carbon::parse($attendance->end_time)->format('H:i')
                    ) }}">
                </td>
            </tr>
            <tr>
                <td colspan="4">

                    @error('start_time')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                    @enderror

                    @error('end_time')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                    @enderror

                </td>
            </tr>

            {{-- 休憩1 --}}
            <tr>

                <th>休憩</th>

                <td>
                    <input
                        type="time"
                        name="break_start[]"
                        value="{{ old(
                'break_start.0',
                isset($attendance->breaks[0])
                    ? \Carbon\Carbon::parse($attendance->breaks[0]->start_time)->format('H:i')
                    : ''
            ) }}">
                </td>

                <td class="attendance-detail__wave">〜</td>

                <td>
                    <input
                        type="time"
                        name="break_end[]"
                        value="{{ old(
                'break_end.0',
                isset($attendance->breaks[0])
                    ? \Carbon\Carbon::parse($attendance->breaks[0]->end_time)->format('H:i')
                    : ''
            ) }}">
                </td>

            </tr>

            {{-- 休憩2 --}}
            <tr>

                <th>休憩2</th>

                <td>
                    <input
                        type="time"
                        name="break_start[]"
                        value="{{ old(
                'break_start.1',
                isset($attendance->breaks[1])
                    ? \Carbon\Carbon::parse($attendance->breaks[1]->start_time)->format('H:i')
                    : ''
            ) }}">
                </td>

                <td class="attendance-detail__wave">〜</td>

                <td>
                    <input
                        type="time"
                        name="break_end[]"
                        value="{{ old(
                'break_end.1',
                isset($attendance->breaks[1])
                    ? \Carbon\Carbon::parse($attendance->breaks[1]->end_time)->format('H:i')
                    : ''
            ) }}">
                </td>

            </tr>
            <tr>
                <td colspan="4">

                    @error('break_start')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                    @enderror

                    @error('break_end')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                    @enderror

                </td>
            </tr>
            {{-- 備考 --}}
            <tr>
                <th>備考</th>

                <td colspan="3">

                    <textarea
                        name="reason"
                        class="attendance-detail__textarea">{{ old('reason') }}</textarea>

                    @error('reason')
                    <p class="error-message">
                        {{ $message }}
                    </p>
                    @enderror

                </td>
            </tr>

        </table>

        {{-- ボタン --}}
        <div class="attendance-detail__button">

            @if ($isPending)

            <p class="attendance-detail__pending">
                *承認待ちのため修正はできません。
            </p>

            @else

            <button type="submit">
                修正
            </button>

            @endif

        </div>

    </form>

</div>

@endsection