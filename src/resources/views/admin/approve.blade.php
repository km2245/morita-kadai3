@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance-detail">

    {{-- タイトル --}}
    <div class="attendance-list__title">

        <div class="attendance-list__line"></div>

        <h1>勤怠詳細</h1>

    </div>

    {{-- テーブル --}}
    <table class="attendance-detail__table">

        {{-- 名前 --}}
        <tr>

            <th>名前</th>

            <td>
                {{ $request->user->name }}
            </td>

        </tr>

        {{-- 日付 --}}
        <tr>

            <th>日付</th>

            <td>
                <div class="attendance-detail__date">

                    <span>
                        {{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                    </span>

                    <span>
                        {{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                    </span>

                </div>
            </td>

        </tr>

        {{-- 出勤退勤 --}}
        <tr>

            <th>出勤・退勤</th>

            <td>
                <div class="attendance-detail__time">

                    <span>
                        {{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}
                    </span>

                    <span class="attendance-detail__wave">
                        〜
                    </span>

                    <span>
                        {{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}
                    </span>

                </div>
            </td>

        </tr>

        {{-- 休憩 --}}
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
        {{-- 備考 --}}
        <tr>

            <th>備考</th>

            <td>
                {{ $request->reason }}
            </td>

        </tr>

    </table>

    {{-- 承認ボタン --}}
    @if ($request->status === 'approved')

    <div class="attendance-detail__button">
        <button
            type="button"
            class="attendance-detail__submit attendance-detail__submit--approved"
            disabled>
            承認済み
        </button>
    </div>

    @else

    <form
        action="{{ route('admin.request.approve.post', $request->id) }}"
        method="POST"
        class="attendance-detail__button">

        @csrf

        <button
            type="submit"
            class="attendance-detail__submit">
            承認
        </button>

    </form>

    @endif

</div>

@endsection