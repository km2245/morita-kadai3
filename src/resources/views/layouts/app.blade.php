<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>勤怠アプリ</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    @yield('css')
</head>

<body>

    <header class="header">

        <div class="header__inner">

            <div class="header-utilities">

                {{-- ロゴ --}}
                <a class="header__logo" href="/">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="COACHTECH">
                </a>

                <nav>

                    <ul class="header-nav">

                        @auth

                        {{-- =========================
                             管理者
                        ========================== --}}
                        @if (auth()->user()->role === 'admin')

                        <li class="header-nav__item">
                            <a
                                class="header-nav__link"
                                href="{{ route('admin.attendance.list') }}">
                                勤怠一覧
                            </a>
                        </li>

                        <li class="header-nav__item">
                            <a
                                class="header-nav__link"
                                href="{{ route('admin.staff.list') }}">
                                スタッフ一覧
                            </a>
                        </li>

                        <li class="header-nav__item">
                            <a
                                class="header-nav__link"
                                href="{{ route('admin.request.list') }}">
                                申請一覧
                            </a>
                        </li>

                        {{-- =========================
                             一般ユーザー
                        ========================== --}}
                        @else

                        <li class="header-nav__item">
                            <a
                                class="header-nav__link"
                                href="{{ route('attendance.create') }}">
                                勤怠
                            </a>
                        </li>

                        <li class="header-nav__item">
                            <a
                                class="header-nav__link"
                                href="{{ route('attendance.index') }}">
                                勤怠一覧
                            </a>
                        </li>

                        <li class="header-nav__item">
                            <a
                                class="header-nav__link"
                                href="{{ route('attendance.request.list') }}">
                                申請
                            </a>
                        </li>

                        @endif

                        {{-- ログアウト --}}
                        <li class="header-nav__item">

                            <form
                                class="form"
                                action="{{ route('logout') }}"
                                method="post">

                                @csrf

                                <button class="header-nav__button">
                                    ログアウト
                                </button>

                            </form>

                        </li>

                        @endauth

                    </ul>

                </nav>

            </div>

        </div>

    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>