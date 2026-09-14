<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>みるまち管理 - @yield('title')</title>

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
            --danger: #A9673B;
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
            color: var(--ink);
        }

        .screen {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           Sidebar
        ========================= */

        .sidebar {
            width: 250px;
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
            padding: 28px 26px 22px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-mark {
            font-family: 'Noto Serif JP', serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #F4F1E8;
        }

        .brand-sub {
            margin-top: 6px;
            font-size: 10.5px;
            letter-spacing: 0.18em;
            color: var(--accent);
        }

        .admin-badge {
            display: inline-block;
            margin-top: 12px;
            font-size: 10px;
            letter-spacing: 0.1em;
            color: var(--panel);
            background: var(--accent);
            padding: 3px 9px;
            border-radius: 20px;
            font-weight: 700;
        }

        nav {
            padding: 14px 12px;
            flex: 1;
        }

        .nav-section-label {
            font-size: 10.5px;
            letter-spacing: 0.12em;
            color: #7C7A6F;
            padding: 10px 16px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            color: #CFCCC1;
            cursor: pointer;
            margin-bottom: 3px;
            transition: background 0.15s ease, color 0.15s ease;
            position: relative;
            text-decoration: none;
        }

        .nav-item svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .nav-item .count {
            margin-left: auto;
            font-size: 11px;
            color: #9C998E;
        }

        .nav-item.active {
            background: var(--panel-soft);
            color: #FFFFFF;
        }

        .nav-item.active::before {
            content: "";
            position: absolute;
            left: -12px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            background: var(--accent);
            border-radius: 2px;
        }

        .nav-item:not(.active):hover {
            color: #EDEAE1;
        }

        .account {
            padding: 18px 26px 24px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-deep);
            color: #F4F1E8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .account-name {
            font-size: 12.5px;
            color: #EDEAE1;
        }

        .account-role {
            font-size: 11px;
            color: #9C998E;
        }

        .logout {
            padding: 14px 26px 22px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 12.5px;
            color: #A9A69C;
            display: flex;
            background: transparent;
            border: none;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            width: 100%;
        }

        .logout:hover {
            color: #EDEAE1;
        }

        .logout svg {
            width: 15px;
            height: 15px;
            opacity: 0.8;
        }

        /* =========================
           Main
        ========================= */

        .main {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: 100vh;
        }

        header {
            padding: 24px 36px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 1px solid var(--line);
            background: var(--card);

            position: sticky;
            top: 0;
            z-index: 10;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--ink-soft);
            margin-bottom: 10px;
        }

        .breadcrumb a {
            color: var(--ink-soft);
            text-decoration: none;
            cursor: pointer;
        }

        .breadcrumb a:hover {
            color: var(--ink);
        }

        header h1 {
            font-family: 'Noto Serif JP', serif;
            font-size: 21px;
            font-weight: 500;
            margin: 0 0 3px;
            color: var(--ink);
            letter-spacing: 0.02em;
        }

        header .desc {
            font-size: 12.5px;
            color: var(--ink-soft);
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            background: var(--panel);
            color: #F4F1E8;
            border: none;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-primary:hover {
            background: var(--panel-soft);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--card);
            color: var(--ink);
            border: 1px solid var(--line);
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #FBFAF6;
        }

        .btn-primary svg, .btn-secondary svg {
            width: 15px;
            height: 15px;
        }

        /* =========================
           Content
        ========================= */

        .content {
            padding: 20px 36px 40px;
            overflow-y: auto;
            flex: 1;
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="screen">

    {{-- サイドバー --}}
    <aside class="sidebar">

        <div class="brand">
            <div class="brand-mark">みるまち</div>
            <div class="brand-sub">COMMUNITY PORTAL</div>
            <div class="admin-badge">ADMIN</div>
        </div>

        <nav>

            <div class="nav-section-label">コンテンツ管理</div>

            {{-- 回覧 --}}
            <a href="{{ route('admin.posts.index') }}"
              class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">

                    <path d="M7 3h10a1 1 0 0 1 1 1v16l-3-2-2 2-2-2-2 2-3-2V4a1 1 0 0 1 1-1Z"/>
                    <path d="M9 8h6M9 12h6"/>

                </svg>

                回覧
                <span class="count">{{ $postsCount ?? '' }}</span>
            </a>


            {{-- イベント --}}
            <a href="{{ route('admin.events.index') }}"
              class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">

                    <rect x="3" y="5" width="18" height="15" rx="1.5"/>
                    <path d="M8 3v4M16 3v4M3 10h18"/>

                </svg>

                イベント
                <span class="count">{{ $eventsCount ?? '' }}</span>
            </a>


            {{-- アンケート --}}
            <a href="{{ route('admin.surveys.index') }}"
              class="nav-item {{ request()->routeIs('admin.surveys.*') ? 'active' : '' }}">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6">

                    <path d="M9 11l2 2 4-4"/>
                    <rect x="3" y="4" width="18" height="16" rx="2"/>

                </svg>

                アンケート
                <span class="count">{{ $surveysCount ?? '' }}</span>
            </a>

        </nav>


        {{-- アカウント情報 --}}
        <div class="account">
            <div class="avatar">{{ mb_substr(auth()->user()->name ?? '管', 0, 1) }}</div>
            <div>
                <div class="account-name">{{ auth()->user()->name ?? '管理者アカウント' }}</div>
                <div class="account-role">{{ auth()->user()->area ?? '中村区 事務局' }}</div>
            </div>
        </div>


        {{-- ログアウト --}}
        <form action="{{ route('admin.logout') }}" method="POST">
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

            <div>
                @hasSection('breadcrumb')
                    <div class="breadcrumb">
                        @yield('breadcrumb')
                    </div>
                @endif

                <h1>@yield('title')</h1>

                @hasSection('description')
                    <div class="desc">@yield('description')</div>
                @endif
            </div>

            @hasSection('header-actions')
                <div class="header-actions">
                    @yield('header-actions')
                </div>
            @endif

        </header>


        {{-- 各ページの中身 --}}
        <div class="content">
            @yield('content')
        </div>

    </div>

</div>

@stack('scripts')

</body>
</html>