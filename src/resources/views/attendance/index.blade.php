@extends('layouts.app')

@section('content')

<h2>{{ $now->format('Y年n月j日') }}</h2>

<p>
    {{ ['日', '月', '火', '水', '木', '金', '土'][$now->dayOfWeek] }}
</p>

<h1>{{ $now->format('H:i') }}</h1>

@if (!$attendance)

{{-- まだ出勤していない → 勤務外 --}}
<p>勤務外</p>

@elseif ($attendance->status === 'working')

{{-- 勤務中 --}}
<p>出勤中</p>

@elseif ($attendance->status === 'break')

{{-- 休憩中 --}}
<p>休憩中</p>

@elseif ($attendance->status === 'finished')

{{-- 退勤済み --}}
<p>退勤済</p>
<p>お疲れ様でした。</p>

@endif


@if (!$attendance)

{{-- 出勤前 → 出勤ボタンだけ --}}
<form action="{{ route('attendance.store') }}" method="POST">
    @csrf
    <button type="submit">出勤</button>
</form>

@elseif ($attendance->status === 'working')

{{-- 勤務中 → 休憩入・退勤 --}}
<form action="{{ route('attendance.break.start') }}" method="POST">
    @csrf
    <button type="submit">休憩入</button>
</form>

<form action="{{ route('attendance.leave') }}" method="POST">
    @csrf
    <button type="submit">退勤</button>
</form>

@elseif ($attendance->status === 'break')

{{-- 休憩中 → 休憩戻 --}}
<form action="{{ route('attendance.break.end') }}" method="POST">
    @csrf
    <button type="submit">休憩戻</button>
</form>

@endif

@endsection