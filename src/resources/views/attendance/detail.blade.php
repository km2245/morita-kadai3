@extends('layouts.app')

@section('content')

<h1>勤怠詳細画面</h1>

<p>日付：{{ $attendance->date }}</p>

<p>出勤時間：{{ $attendance->start_time }}</p>

<p>退勤時間：{{ $attendance->end_time }}</p>

<h2>休憩時間</h2>

@foreach ($attendance->breaks as $break)
<p>
    {{ $break->start_time }}
    〜
    {{ $break->end_time }}
</p>
<h2>修正申請</h2>

<form action="{{ route('attendance.request', $attendance->id) }}" method="POST">
    @csrf

    {{-- 備考（修正理由） --}}
    <div>
        <label>備考</label><br>
        <textarea name="reason" rows="4" cols="40"></textarea>

        @error('reason')
        <p class="error-message">{{ $message }}</p>
        @enderror
    </div>

    <br>

    {{-- 修正ボタン --}}
    <button type="submit">
        修正
    </button>
</form>
@endforeach

@endsection