@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')

<div class="attendance">


    {{-- ステータス --}}
    <p class="attendance__status attendance__status--badge">
        @if (!$attendance)
        勤務外
        @elseif ($attendance->status === 'working')
        出勤中
        @elseif ($attendance->status === 'break')
        休憩中
        @elseif ($attendance->status === 'finished')
        退勤済
        @endif
    </p>


    {{-- 日付 --}}
    <p class="attendance__date">
        {{ $now->format('Y年n月j日') }}（{{ ['日','月','火','水','木','金','土'][$now->dayOfWeek] }}）
    </p>

    {{-- 時刻 --}}
    <h1 class="attendance__time">
        {{ $now->format('H:i') }}
    </h1>



    {{-- ボタン --}}
    <div class="attendance__buttons">

        @if (!$attendance)

        {{-- 出勤前 --}}
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <button class="attendance__button attendance__button--primary">
                出勤
            </button>
        </form>

        @elseif ($attendance->status === 'working')

        {{-- 勤務中 --}}
        <form action="{{ route('attendance.leave') }}" method="POST">
            @csrf
            <button class="attendance__button attendance__button--primary">
                退勤
            </button>
        </form>

        <form action="{{ route('attendance.break.start') }}" method="POST">
            @csrf
            <button class="attendance__button attendance__button--white">
                休憩入
            </button>
        </form>

        @elseif ($attendance->status === 'break')

        {{-- 休憩中 --}}
        <form action="{{ route('attendance.break.end') }}" method="POST">
            @csrf
            <button class="attendance__button attendance__button--white">
                休憩戻
            </button>
        </form>

        @elseif ($attendance->status === 'finished')

        <p class="attendance__message">
            お疲れ様でした。
        </p>

        
        @endif




    </div>

</div>

@endsection