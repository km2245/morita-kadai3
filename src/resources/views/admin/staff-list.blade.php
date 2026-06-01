@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')

<div class="staff-list">

    {{-- タイトル --}}
    <div class="staff-list__title">

        <div class="staff-list__line"></div>

        <h1>スタッフ一覧</h1>

    </div>

    {{-- テーブル --}}
    <table class="staff-table">

        <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
        </tr>

        @foreach ($users as $user)

        <tr>

            <td>
                {{ $user->name }}
            </td>

            <td>
                {{ $user->email }}
            </td>

            <td>

                <a href="{{ route('admin.staff.attendance', $user->id) }}">
                    詳細
                </a>

            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection