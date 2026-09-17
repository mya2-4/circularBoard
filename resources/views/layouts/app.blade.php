<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>みるまち</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@500;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #F6F4EE;
            --panel: #1E2B25;
            --panel-soft: #263832;
            --accent: #7C9885;
            --accent-deep: #4E6B5A;
            --ink: #23241F;
            --ink-soft: #5B5A52;
            --line: #E2DFD3;
            --card: #FFFFFF;
            --new-tag: #A9673B;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: var(--bg);
        }

        .screen {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           Sidebar
        ========================= */

        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: var(--panel);
            color: #EDEAE1;
            display: flex;
            flex-direction: column;

            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            height: 100vh;
        }

        .brand {
            padding: 32px 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-mark {
            font-family: 'Noto Serif JP', serif;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #F4F1E8;
        }

        .brand-sub {
            margin-top: 6px;
            font-size: 11px;
            letter-spacing: 0.18em;
            color: var(--accent);
        }

        .location {
            padding: 20px 28px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-size: 13px;
            color: #C9C6BB;
            line-height: 1.6;
        }

        .location strong {
            display: block;
            color: #EDEAE1;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        nav {
            padding: 14px 14px;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 8px;
            font-size: 14px;
            color: #CFCCC1;
            cursor: pointer;
            margin-bottom: 4px;
            transition: background 0.15s ease, color 0.15s ease;
            position: relative;
            text-decoration: none;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .nav-item.active {
            background: var(--panel-soft);
            color: #FFFFFF;
        }

        .nav-item.active::before {
            content: "";
            position: absolute;
            left: -14px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            background: var(--accent);
            border-radius: 2px;
        }

        .nav-item:not(.active):hover {
            color: #EDEAE1;
        }

        .logout {
            padding: 20px 28px 26px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 13px;
            color: #A9A69C;
            display: flex;
            background: transparent;
            border: none;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .logout svg {
            width: 16px;
            height: 16px;
            opacity: 0.8;
        }

        /* =========================
           Main
        ========================= */

        .main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: 100vh;
        }

        header {
            padding: 26px 40px;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            border-bottom: 1px solid var(--line);
            background: var(--card);

            position: sticky;
            top: 0;
            z-index: 10;
        }

        header h1 {
            font-family: 'Noto Serif JP', serif;
            font-size: 22px;
            font-weight: 500;
            margin: 0;
            color: var(--ink);
            letter-spacing: 0.02em;
        }

        header .greet {
            font-size: 13px;
            color: var(--ink-soft);
        }

        header .greet b {
            color: var(--ink);
            font-weight: 500;
        }

        /* =========================
           Content
        ========================= */

        .content {
            padding: 8px 40px 40px;
            overflow-y: auto;
            flex: 1;
        }
    </style>
</head>

<body>

<div class="screen">

    {{-- サイドバー --}}
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-mark">みるまち</div>
            <div class="brand-sub">COMMUNITY PORTAL</div>
        </div>

        <div class="location">
            <strong>{{ auth()->user()->region }}</strong>
            {{ auth()->user()->region2 }}
        </div>

        <nav>

            {{-- ホーム --}}
            <a href="{{ route('home') }}"
              class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">

                    <path d="M4 11.5 12 4l8 7.5"/>
                    <path d="M6 10v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-9"/>

                </svg>

                ホーム
            </a>


            {{-- イベント --}}
            <a href="{{ route('events.index') }}"
              class="nav-item {{ request()->routeIs('events.*') ? 'active' : '' }}">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">

                    <rect x="3" y="5" width="18" height="15" rx="1.5"/>
                    <path d="M8 3v4M16 3v4M3 10h18"/>

                </svg>

                イベント
            </a>


            {{-- アンケート --}}
            <a href="{{ route('surveys.index') }}"
              class="nav-item {{ request()->routeIs('surveys.*') ? 'active' : '' }}">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">

                    <path d="M9 11l2 2 4-4"/>
                    <rect x="3" y="4" width="18" height="16" rx="2"/>

                </svg>

                アンケート
            </a>

        </nav>


        {{-- ログアウト --}}
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="logout">

              <svg viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.6">

                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                  <path d="M16 17l5-5-5-5M21 12H9"/>

              </svg>

              ログアウト

          </button>
        </form>

    </aside>


    {{-- メイン --}}
    <div class="main">

        {{-- 上部ヘッダー --}}
        <header>

            <h1>
                @yield('title')
            </h1>

            <div class="greet">
                こんにちは、<b>{{ auth()->user()->last_name }}さん</b>
            </div>

        </header>


        {{-- 各ページの中身 --}}
        @yield('content')

    </div>

</div>

</body>
</html>