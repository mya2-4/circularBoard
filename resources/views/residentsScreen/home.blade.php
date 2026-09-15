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
  *{box-sizing:border-box;}
  html,body{ height:100%; margin:0; }
  body{
    font-family:'Noto Sans JP', sans-serif;
    background:var(--bg);
  }
  .screen{
    display:flex;
    min-height:100vh;
  }
  /* Sidebar */
  .sidebar{
    width:260px;
    flex-shrink:0;
    background:var(--panel);
    color:#EDEAE1;
    display:flex;
    flex-direction:column;
  }
  .brand{
    padding:32px 28px 24px;
    border-bottom:1px solid rgba(255,255,255,0.08);
  }
  .brand-mark{
    font-family:'Noto Serif JP', serif;
    font-size:26px;
    font-weight:700;
    letter-spacing:0.04em;
    color:#F4F1E8;
  }
  .brand-sub{
    margin-top:6px;
    font-size:11px;
    letter-spacing:0.18em;
    color:var(--accent);
  }
  .location{
    padding:20px 28px;
    border-bottom:1px solid rgba(255,255,255,0.08);
    font-size:13px;
    color:#C9C6BB;
    line-height:1.6;
  }
  .location strong{
    display:block;
    color:#EDEAE1;
    font-size:14px;
    font-weight:500;
    margin-bottom:2px;
  }
  nav{ padding:14px 14px; flex:1; }
  .nav-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:13px 16px;
    border-radius:8px;
    font-size:14px;
    color:#CFCCC1;
    cursor:pointer;
    margin-bottom:4px;
    transition: background 0.15s ease, color 0.15s ease;
    position:relative;
    text-decoration:none;
  }
  .nav-item svg{ width:18px; height:18px; flex-shrink:0; opacity:0.85; }
  .nav-item.active{
    background: var(--panel-soft);
    color:#FFFFFF;
  }
  .nav-item.active::before{
    content:"";
    position:absolute;
    left:-14px;
    top:8px;
    bottom:8px;
    width:3px;
    background:var(--accent);
    border-radius:2px;
  }
  .nav-item:not(.active):hover{ color:#EDEAE1; }
  .logout{
    padding:20px 28px 26px;
    border-top:1px solid rgba(255,255,255,0.08);
    font-size:13px;
    color:#A9A69C;
    display:flex;
    align-items:center;
    gap:10px;
    cursor:pointer;
  }
  .logout svg{ width:16px; height:16px; opacity:0.8; }

  /* Main */
  .main{ flex:1; display:flex; flex-direction:column; min-width:0; }
  header{
    padding:26px 40px;
    display:flex;
    align-items:baseline;
    justify-content:space-between;
    border-bottom:1px solid var(--line);
    background:var(--card);
  }
  header h1{
    font-family:'Noto Serif JP', serif;
    font-size:22px;
    font-weight:500;
    margin:0;
    color:var(--ink);
    letter-spacing:0.02em;
  }
  header .greet{
    font-size:13px;
    color:var(--ink-soft);
  }
  header .greet b{ color:var(--ink); font-weight:500; }

  .tabs{
    display:flex;
    gap:6px;
    padding:16px 40px 0;
    background:var(--bg);
  }
  .tab{
    padding:10px 4px;
    font-size:13px;
    color:var(--ink-soft);
    border-bottom:2px solid transparent;
    cursor:pointer;
    margin-right:28px;
    text-decoration:none;
  }
  .tab.active{
    color:var(--ink);
    font-weight:700;
    border-bottom-color: var(--accent-deep);
  }

  .content{
    padding:8px 40px 40px;
    overflow-y:auto;
  }
  .post{
    padding:26px 0;
    border-bottom:1px solid var(--line);
  }
  .post:last-child{ border-bottom:none; }
  .post-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:6px;
  }
  .post-head-left{
    display:flex;
    align-items:center;
    gap:10px;
  }
  .post-head h2{
    font-family:'Noto Serif JP', serif;
    font-size:18px;
    font-weight:500;
    margin:0;
    color:var(--ink);
  }
  .post-head h2 a{
    color:inherit;
    text-decoration:none;
  }
  .post-head h2 a:hover{ text-decoration:underline; }

  .new-badge{
    font-size:11px;
    color:var(--new-tag);
    border:1px solid var(--new-tag);
    padding:2px 8px;
    border-radius:20px;
    letter-spacing:0.05em;
    flex-shrink:0;
  }

  .post-meta{
    font-size:12px;
    color:#9A978C;
    margin-bottom:10px;
  }

  .post p{
    margin:0;
    font-size:14px;
    line-height:1.9;
    color:var(--ink-soft);
    max-width:640px;
  }

  .read-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:11px;
    padding:4px 12px;
    border-radius:20px;
    font-weight:700;
    letter-spacing:0.03em;
    flex-shrink:0;
  }
  .read-badge svg{ width:12px; height:12px; }
  .read-badge.unread{ background:#F7ECE8; color:#A9673B; }
  .read-badge.read{ background:#EFF0EC; color:#6E7A70; }
  .read-badge.confirmed{ background:#EAF1EC; color:#4E6B5A; }

  .no-posts {
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color:#9A978C;
    padding:60px 0;
  }
</style>
</head>
<body>
<div class="screen">
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">みるまち</div>
      <div class="brand-sub">COMMUNITY PORTAL</div>
    </div>
    <div class="location">
      <strong>名古屋市中村区</strong>
      名駅2丁目
    </div>
    <nav>
    <a href="{{ route('home') }}"
        class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M4 11.5 12 4l8 7.5"/>
            <path d="M6 10v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-9"/>
        </svg>
        ホーム
    </a>

    <a href="{{ route('events.index') }}"
        class="nav-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <rect x="3" y="5" width="18" height="15" rx="1.5"/>
            <path d="M8 3v4M16 3v4M3 10h18"/>
        </svg>
        イベント
    </a>

    <a href="{{ route('surveys.index') }}"
        class="nav-item {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M9 11l2 2 4-4"/>
            <rect x="3" y="4" width="18" height="16" rx="2"/>
        </svg>
        アンケート
    </a>
</nav>
    <div class="logout">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
      ログアウト
    </div>
  </aside>

  <div class="main">
    <header>
      <h1>ホーム</h1>
      <div class="greet">こんにちは、<b>{{ auth()->user()->name ?? 'ゲスト' }}さん</b></div>
    </header>
    <div class="tabs">
      <a href="{{ route('home') }}" class="tab {{ request('view') === null ? 'active' : '' }}">すべて</a>
      <a href="{{ route('home', ['view' => 'confirmed']) }}" class="tab {{ request('view') === 'confirmed' ? 'active' : '' }}">既読・確認済み</a>
      <a href="{{ route('home', ['view' => 'unread']) }}" class="tab {{ request('view') === 'unread' ? 'active' : '' }}">未読</a>
    </div>
    <div class="content">
        @forelse ($posts as $post)
            <div class="post">
                <div class="post-head">
                    <div class="post-head-left">
                        <h2><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></h2>

                        @if ($post->is_new)
                            <span class="new-badge">新着</span>
                        @endif
                    </div>

                    @if ($post->read_status === 'confirmed')
                        <span class="read-badge confirmed">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m5 13 4 4L19 7"/></svg>
                            確認済み
                        </span>
                    @elseif ($post->read_status === 'read')
                        <span class="read-badge read">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            既読
                        </span>
                    @else
                        <span class="read-badge unread">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="m4 6 8 7 8-7"/></svg>
                            未読
                        </span>
                    @endif
                </div>

                <div class="post-meta">
                    {{ $post->published_at?->format('Y年n月j日') }}
                </div>

                <p>{{ $post->summary ?? \Illuminate\Support\Str::limit($post->body, 80) }}</p>
            </div>
        @empty
            <p class="no-posts">新着回覧がありません</p>
        @endforelse
    </div>
  </div>
</div>
</body>
</html>