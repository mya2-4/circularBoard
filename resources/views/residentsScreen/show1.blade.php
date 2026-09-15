<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>みるまち管理 - 回覧の閲覧状況</title>
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
    --status-confirmed-bg: #EAF1EC;
    --status-confirmed-text: #4E6B5A;
    --status-read-bg: #EFF0EC;
    --status-read-text: #6E7A70;
    --status-unread-bg: #F7ECE8;
    --status-unread-text: #A9673B;
  }
  *{box-sizing:border-box;}
  html,body{ height:100%; margin:0; }
  body{
    font-family:'Noto Sans JP', sans-serif;
    background:var(--bg);
    color:var(--ink);
  }
  .screen{ display:flex; min-height:100vh; }

  /* Sidebar */
  .sidebar{
    width:250px;
    flex-shrink:0;
    background:var(--panel);
    color:#EDEAE1;
    display:flex;
    flex-direction:column;
  }
  .brand{ padding:28px 26px 22px; border-bottom:1px solid rgba(255,255,255,0.08); }
  .brand-mark{ font-family:'Noto Serif JP', serif; font-size:22px; font-weight:700; letter-spacing:0.03em; color:#F4F1E8; }
  .brand-sub{ margin-top:6px; font-size:10.5px; letter-spacing:0.18em; color:var(--accent); }
  .admin-badge{
    display:inline-block; margin-top:12px; font-size:10px; letter-spacing:0.1em;
    color:var(--panel); background:var(--accent); padding:3px 9px; border-radius:20px; font-weight:700;
  }
  nav{ padding:14px 12px; flex:1; }
  .nav-section-label{ font-size:10.5px; letter-spacing:0.12em; color:#7C7A6F; padding:10px 16px 6px; }
  .nav-item{
    display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:8px;
    font-size:14px; color:#CFCCC1; cursor:pointer; margin-bottom:3px;
    transition: background 0.15s ease, color 0.15s ease; position:relative;
  }
  .nav-item svg{ width:17px; height:17px; flex-shrink:0; opacity:0.85; }
  .nav-item .count{ margin-left:auto; font-size:11px; color:#9C998E; }
  .nav-item.active{ background: var(--panel-soft); color:#FFFFFF; }
  .nav-item.active::before{
    content:""; position:absolute; left:-12px; top:8px; bottom:8px; width:3px;
    background:var(--accent); border-radius:2px;
  }
  .nav-item:not(.active):hover{ color:#EDEAE1; }
  .account{
    padding:18px 26px 24px; border-top:1px solid rgba(255,255,255,0.08);
    display:flex; align-items:center; gap:10px;
  }
  .avatar{
    width:32px; height:32px; border-radius:50%; background:var(--accent-deep); color:#F4F1E8;
    display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0;
  }
  .account-name{ font-size:12.5px; color:#EDEAE1; }
  .account-role{ font-size:11px; color:#9C998E; }

  /* Main */
  .main{ flex:1; display:flex; flex-direction:column; min-width:0; }
  header{
    padding:22px 36px;
    border-bottom:1px solid var(--line);
    background:var(--card);
  }
  .breadcrumb{
    display:flex; align-items:center; gap:6px;
    font-size:12px; color:var(--ink-soft); margin-bottom:12px;
  }
  .breadcrumb a{ color:var(--ink-soft); text-decoration:none; cursor:pointer; }
  .breadcrumb a:hover{ color:var(--ink); }
  .header-row{
    display:flex; align-items:flex-start; justify-content:space-between;
  }
  header h1{
    font-family:'Noto Serif JP', serif; font-size:20px; font-weight:500;
    margin:0 0 6px; color:var(--ink);
  }
  header .desc{ font-size:12.5px; color:var(--ink-soft); display:flex; gap:14px; }
  .desc .status-badge{
    font-size:11px; padding:3px 10px; border-radius:20px; background:var(--status-confirmed-bg); color:var(--status-confirmed-text); font-weight:500;
  }

  .btn-secondary{
    display:inline-flex; align-items:center; gap:8px; padding:10px 16px;
    background:var(--card); color:var(--ink); border:1px solid var(--line); border-radius:6px;
    font-size:13px; font-weight:500; cursor:pointer;
  }
  .btn-secondary:hover{ background:#FBFAF6; }
  .btn-secondary svg{ width:15px; height:15px; }

  /* Stat cards */
  .stats-row{ display:flex; gap:16px; padding:20px 36px 0; }
  .stat-card{
    flex:1; background:var(--card); border:1px solid var(--line); border-radius:10px; padding:18px 20px;
  }
  .stat-card.highlight{ border-color: var(--accent-deep); }
  .stat-label{ font-size:11.5px; color:var(--ink-soft); margin-bottom:8px; display:flex; align-items:center; gap:6px; }
  .dot{ width:8px; height:8px; border-radius:50%; }
  .dot.confirmed{ background:var(--status-confirmed-text); }
  .dot.read{ background:var(--status-read-text); }
  .dot.unread{ background:var(--status-unread-text); }
  .stat-value{ font-family:'Noto Serif JP', serif; font-size:26px; font-weight:500; color:var(--ink); }
  .stat-value span{ font-size:13px; font-family:'Noto Sans JP', sans-serif; color:var(--ink-soft); font-weight:400; margin-left:4px; }

  /* Overall progress bar */
  .overall-bar-wrap{ padding:18px 36px 0; }
  .overall-bar{
    display:flex; height:10px; border-radius:6px; overflow:hidden; background:var(--line);
  }
  .overall-bar .seg.confirmed{ background: var(--status-confirmed-text); }
  .overall-bar .seg.read{ background: var(--status-read-text); }
  .overall-bar .seg.unread{ background: var(--status-unread-text); opacity:0.55; }
  .overall-legend{
    display:flex; gap:20px; margin-top:10px; font-size:12px; color:var(--ink-soft);
  }
  .overall-legend .item{ display:flex; align-items:center; gap:6px; }

  .toolbar{
    display:flex; align-items:center; justify-content:space-between; padding:22px 36px 0;
  }
  .tabs{ display:flex; gap:0; }
  .tab{
    padding:10px 4px; margin-right:26px; font-size:13px; color:var(--ink-soft);
    border-bottom:2px solid transparent; cursor:pointer; display:flex; align-items:center; gap:6px;
  }
  .tab .tab-count{ font-size:11px; color:#9A978C; }
  .tab.active{ color:var(--ink); font-weight:700; border-bottom-color: var(--accent-deep); }
  .tab.active .tab-count{ color:var(--ink-soft); }

  .search-box{
    display:flex; align-items:center; gap:8px; background:var(--card); border:1px solid var(--line);
    border-radius:6px; padding:9px 12px; width:240px;
  }
  .search-box svg{ width:15px; height:15px; color:#9A978C; flex-shrink:0; }
  .search-box input{ border:none; outline:none; font-size:13px; font-family:inherit; width:100%; color:var(--ink); background:transparent; }
  .search-box input::placeholder{ color:#B9B6AA; }

  .content{ padding:20px 36px 40px; overflow-y:auto; }

  .table-card{ background:var(--card); border:1px solid var(--line); border-radius:10px; overflow:hidden; }
  table{ width:100%; border-collapse:collapse; }
  thead th{
    text-align:left; font-size:11.5px; letter-spacing:0.04em; color:var(--ink-soft); font-weight:500;
    padding:14px 20px; background:#FBFAF6; border-bottom:1px solid var(--line);
  }
  tbody td{
    padding:14px 20px; font-size:13.5px; color:var(--ink); border-bottom:1px solid var(--line); vertical-align:middle;
  }
  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:#FBFAF6; }

  .user-cell{ display:flex; align-items:center; gap:10px; }
  .user-avatar{
    width:30px; height:30px; border-radius:50%; background:var(--line); color:var(--ink-soft);
    display:flex; align-items:center; justify-content:center; font-size:11.5px; font-weight:700; flex-shrink:0;
  }
  .user-name{ font-weight:500; }
  .user-sub{ font-size:11.5px; color:#9A978C; margin-top:2px; }

  .status-badge{
    display:inline-flex; align-items:center; gap:6px; font-size:11.5px; padding:5px 12px; border-radius:20px; font-weight:500;
  }
  .status-badge svg{ width:13px; height:13px; }
  .status-badge.confirmed{ background:var(--status-confirmed-bg); color:var(--status-confirmed-text); }
  .status-badge.read{ background:var(--status-read-bg); color:var(--status-read-text); }
  .status-badge.unread{ background:var(--status-unread-bg); color:var(--status-unread-text); }

  .timestamp{ font-size:12.5px; color:var(--ink-soft); }
  .timestamp .muted{ color:#B9B6AA; }

  .row-actions{ display:flex; gap:6px; justify-content:flex-end; }
  .icon-btn{
    width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:6px;
    border:1px solid var(--line); background:var(--card); color:var(--ink-soft); cursor:pointer;
    transition: background 0.15s ease, color 0.15s ease;
  }
  .icon-btn:hover{ background:#F1EEE6; color:var(--ink); }
  .icon-btn svg{ width:15px; height:15px; }

  .pagination{
    display:flex; align-items:center; justify-content:space-between; padding:18px 4px 0; font-size:12.5px; color:var(--ink-soft);
  }
  .page-btns{ display:flex; gap:6px; }
  .page-btn{
    width:30px; height:30px; display:flex; align-items:center; justify-content:center; border-radius:6px;
    border:1px solid var(--line); background:var(--card); font-size:12.5px; color:var(--ink-soft); cursor:pointer;
  }
  .page-btn.active{ background:var(--panel); color:#F4F1E8; border-color:var(--panel); }

  .back-link{
    display:inline-flex; align-items:center; gap:6px;
    margin-top:24px; font-size:13px; color:var(--ink-soft); text-decoration:none;
  }
  .back-link:hover{ color:var(--ink); }
  .back-link svg{ width:14px; height:14px; }
</style>
</head>
<body>
<div class="screen">
  <aside class="sidebar">
    <div class="brand">
      <div class="brand-mark">みるまち</div>
      <div class="brand-sub">COMMUNITY PORTAL</div>
      <div class="admin-badge">ADMIN</div>
    </div>
    <nav>
      <div class="nav-section-label">コンテンツ管理</div>
      <div class="nav-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M7 3h10a1 1 0 0 1 1 1v16l-3-2-2 2-2-2-2 2-3-2V4a1 1 0 0 1 1-1Z"/><path d="M9 8h6M9 12h6"/></svg>
        回覧
        <span class="count">12</span>
      </div>
      <div class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="15" rx="1.5"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
        イベント
        <span class="count">5</span>
      </div>
      <div class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 11l2 2 4-4"/><rect x="3" y="4" width="18" height="16" rx="2"/></svg>
        アンケート
        <span class="count">3</span>
      </div>
    </nav>
    <div class="account">
      <div class="avatar">管</div>
      <div>
        <div class="account-name">管理者アカウント</div>
        <div class="account-role">中村区 事務局</div>
      </div>
    </div>
  </aside>

  <div class="main">
    <header>
    <div class="breadcrumb">
      <a href="{{ route('admin.posts.index') }}">回覧管理</a>
      <span>／</span>
      <span>閲覧状況</span>
    </div>
    <div class="header-row">
      <div>
        <h1>{{ $post->title }}</h1>
        <div class="desc">
          公開日：{{ $post->published_at?->format('Y/m/d') ?? '—' }}
          <span class="status-badge">{{ $post->status === 'public' ? '公開中' : '下書き' }}</span>
        </div>
      </div>
      ...
    </div>
    </header>

    <div class="stats-row">
      <div class="stat-card highlight">
        <div class="stat-label"><span class="dot confirmed"></span>確認済み</div>
        <div class="stat-value">{{ $confirmedCount }}<span>/ {{ $totalCount }}世帯</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label"><span class="dot read"></span>既読（未確認）</div>
        <div class="stat-value">{{ $readCount }}<span>/ {{ $totalCount }}世帯</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label"><span class="dot unread"></span>未読</div>
        <div class="stat-value">{{ $unreadCount }}<span>/ {{ $totalCount }}世帯</span></div>
      </div>
    </div>

    <div class="overall-bar-wrap">
      @php
        $confirmedPct = $totalCount ? round($confirmedCount / $totalCount * 100) : 0;
        $readPct      = $totalCount ? round($readCount / $totalCount * 100) : 0;
        $unreadPct    = $totalCount ? round($unreadCount / $totalCount * 100) : 0;
      @endphp
      <div class="overall-bar">
        <div class="seg confirmed" style="width:{{ $confirmedPct }}%"></div>
        <div class="seg read" style="width:{{ $readPct }}%"></div>
        <div class="seg unread" style="width:{{ $unreadPct }}%"></div>
      </div>
      <div class="overall-legend">
        <div class="item"><span class="dot confirmed"></span>確認済み {{ $confirmedPct }}%</div>
        <div class="item"><span class="dot read"></span>既読 {{ $readPct }}%</div>
        <div class="item"><span class="dot unread"></span>未読 {{ $unreadPct }}%</div>
      </div>
    </div>

    <div class="toolbar">
      <div class="tabs">
        <a href="{{ route('admin.posts.show', $post->id) }}" class="tab {{ request('status') === null ? 'active' : '' }}">
          すべて <span class="tab-count">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('admin.posts.show', ['post' => $post->id, 'status' => 'confirmed']) }}" class="tab {{ request('status') === 'confirmed' ? 'active' : '' }}">
          確認済み <span class="tab-count">{{ $confirmedCount }}</span>
        </a>
        <a href="{{ route('admin.posts.show', ['post' => $post->id, 'status' => 'read']) }}" class="tab {{ request('status') === 'read' ? 'active' : '' }}">
          既読 <span class="tab-count">{{ $readCount }}</span>
        </a>
        <a href="{{ route('admin.posts.show', ['post' => $post->id, 'status' => 'unread']) }}" class="tab {{ request('status') === 'unread' ? 'active' : '' }}">
          未読 <span class="tab-count">{{ $unreadCount }}</span>
        </a>
      </div>
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="text" placeholder="世帯・氏名で検索">
      </div>
    </div>

    <div class="content">
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th style="width:28%">住民</th>
              <th>世帯・地域</th>
              <th>ステータス</th>
              <th>既読日時</th>
              <th>確認日時</th>
              <th style="text-align:right">操作</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($reads as $read)
              <tr>
                <td>
                  <div class="user-cell">
                    <div class="user-avatar">{{ mb_substr($read->user->last_name ?? $read->user->name, 0, 1) }}</div>
                    <div>
                      <div class="user-name">{{ $read->user->last_name }} {{ $read->user->first_name }}</div>
                      <div class="user-sub">{{ $read->user->region2 }}</div>
                    </div>
                  </div>
                </td>
                <td>{{ $read->user->region }}</td>
                <td>
                  @if ($read->status === 'confirmed')
                    <span class="status-badge confirmed">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 13 4 4L19 7"/></svg>
                      確認済み
                    </span>
                  @elseif ($read->status === 'read')
                    <span class="status-badge read">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                      既読
                    </span>
                  @else
                    <span class="status-badge unread">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="14" rx="2"/><path d="m4 6 8 7 8-7"/></svg>
                      未読
                    </span>
                  @endif
                </td>
                <td class="timestamp">{{ $read->read_at?->format('m/d H:i') ?? '—' }}</td>
                <td class="timestamp">{{ $read->confirmed_at?->format('m/d H:i') ?? '—' }}</td>
                <td>
                  <div class="row-actions">
                    <div class="icon-btn">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 5c-7 0-9.5 7-9.5 7s2.5 7 9.5 7 9.5-7 9.5-7-2.5-7-9.5-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center; color:var(--ink-soft); padding:40px 0;">閲覧記録がありません</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">
        <span>全{{ $reads->total() }}件中 {{ $reads->firstItem() }}〜{{ $reads->lastItem() }}件を表示</span>
        {{ $reads->links() }}
      </div>
      <a href="{{ route('admin.posts.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        ホームに戻る
      </a>
    </div>
  </div>
</div>
</body>
</html>