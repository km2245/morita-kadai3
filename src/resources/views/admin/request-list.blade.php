@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')

<div class="admin">

    {{-- タイトル --}}
    <div class="attendance-list__title">

        <div class="attendance-list__line"></div>

        <h1>申請一覧</h1>

    </div>

    {{-- タブ --}}
    <div class="request-list__tabs">

        <a
            href="{{ route('admin.request.list', ['tab' => 'pending']) }}"
            class="{{ $tab === 'pending' ? 'active' : '' }}">

            承認待ち

        </a>

        <a
            href="{{ route('admin.request.list', ['tab' => 'approved']) }}"
            class="{{ $tab === 'approved' ? 'active' : '' }}">

            承認済み

        </a>

    </div>

    {{-- テーブル --}}
    <table class="staff-table">

        <tr>

            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>

        </tr>

        @foreach ($requests as $request)

        <tr>

            <td>
                {{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}
            </td>

            <td>
                {{ optional($request->user)->name }}
            </td>

            <td>
                {{ optional($request->attendance)->date }}
            </td>

            <td>
                {{ $request->reason }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}
            </td>

            <td>

                <a
                    href="{{ route('admin.request.approve', $request->id) }}">

                    詳細

                </a>

            </td>

        </tr>

        @endforeach

    </table>

</div>

@endsection