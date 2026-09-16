<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>みるまち管理 - イベント管理</title>
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
    --status-open-bg: #EAF1EC;
    --status-open-text: #4E6B5A;
    --status-closed-bg: #F1EEE6;
    --status-closed-text: #8A7A5C;
    --status-ended-bg: #EFEBE6;
    --status-ended-text: #9A978C;
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
  .brand{
    padding:28px 26px 22px;
    border-bottom:1px solid rgba(255,255,255,0.08);
  }
  .brand-mark{
    font-family:'Noto Serif JP', serif;
    font-size:22px;
    font-weight:700;
    letter-spacing:0.03em;
    color:#F4F1E8;
  }
  .brand-sub{
    margin-top:6px;
    font-size:10.5px;
    letter-spacing:0.18em;
    color:var(--accent);
  }
  .admin-badge{
    display:inline-block;
    margin-top:12px;
    font-size:10px;
    letter-spacing:0.1em;
    color:var(--panel);
    background:var(--accent);
    padding:3px 9px;
    border-radius:20px;
    font-weight:700;
  }
  nav{ padding:14px 12px; flex:1; }
  .nav-section-label{
    font-size:10.5px;
    letter-spacing:0.12em;
    color:#7C7A6F;
    padding:10px 16px 6px;
  }
  .nav-item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 16px;
    border-radius:8px;
    font-size:14px;
    color:#CFCCC1;
    cursor:pointer;
    margin-bottom:3px;
    transition: background 0.15s ease, color 0.15s ease;
    position:relative;
  }
  .nav-item svg{ width:17px; height:17px; flex-shrink:0; opacity:0.85; }
  .nav-item .count{
    margin-left:auto;
    font-size:11px;
    color:#9C998E;
  }
  .nav-item.active{
    background: var(--panel-soft);
    color:#FFFFFF;
  }
  .nav-item.active::before{
    content:"";
    position:absolute;
    left:-12px;
    top:8px;
    bottom:8px;
    width:3px;
    background:var(--accent);
    border-radius:2px;
  }
  .nav-item:not(.active):hover{ color:#EDEAE1; }
  .account{
    padding:18px 26px 24px;
    border-top:1px solid rgba(255,255,255,0.08);
    display:flex;
    align-items:center;
    gap:10px;
  }
  .avatar{
    width:32px; height:32px;
    border-radius:50%;
    background:var(--accent-deep);
    color:#F4F1E8;
    display:flex; align-items:center; justify-content:center;
    font-size:12px; font-weight:700;
    flex-shrink:0;
  }
  .account-name{ font-size:12.5px; color:#EDEAE1; }
  .account-role{ font-size:11px; color:#9C998E; }

  /* Main */
  .main{ flex:1; display:flex; flex-direction:column; min-width:0; }
  header{
    padding:24px 36px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    border-bottom:1px solid var(--line);
    background:var(--card);
  }
  header h1{
    font-family:'Noto Serif JP', serif;
    font-size:21px;
    font-weight:500;
    margin:0 0 3px;
    color:var(--ink);
  }
  header .desc{ font-size:12.5px; color:var(--ink-soft); }

  .btn-primary{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:11px 18px;
    background:var(--panel);
    color:#F4F1E8;
    border:none;
    border-radius:6px;
    font-size:13.5px;
    font-weight:700;
    letter-spacing:0.03em;
    cursor:pointer;
    transition: background 0.15s ease;
  }
  .btn-primary:hover{ background:var(--panel-soft); }
  .btn-primary svg{ width:15px; height:15px; }

  /* Stat cards */
  .stats-row{
    display:flex;
    gap:16px;
    padding:20px 36px 0;
  }
  .stat-card{
    flex:1;
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    padding:18px 20px;
  }
  .stat-label{
    font-size:11.5px;
    color:var(--ink-soft);
    margin-bottom:8px;
  }
  .stat-value{
    font-family:'Noto Serif JP', serif;
    font-size:26px;
    font-weight:500;
    color:var(--ink);
  }
  .stat-value span{
    font-size:13px;
    font-family:'Noto Sans JP', sans-serif;
    color:var(--ink-soft);
    font-weight:400;
    margin-left:4px;
  }

  .toolbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:22px 36px 0;
  }
  .tabs{ display:flex; gap:0; }
  .tab{
    padding:10px 4px;
    margin-right:26px;
    font-size:13px;
    color:var(--ink-soft);
    border-bottom:2px solid transparent;
    cursor:pointer;
  }
  .tab.active{
    color:var(--ink);
    font-weight:700;
    border-bottom-color: var(--accent-deep);
  }
  .search-box{
    display:flex;
    align-items:center;
    gap:8px;
    background:var(--card);
    border:1px solid var(--line);
    border-radius:6px;
    padding:9px 12px;
    width:260px;
  }
  .search-box svg{ width:15px; height:15px; color:#9A978C; flex-shrink:0; }
  .search-box input{
    border:none;
    outline:none;
    font-size:13px;
    font-family:inherit;
    width:100%;
    color:var(--ink);
    background:transparent;
  }
  .search-box input::placeholder{ color:#B9B6AA; }

  .content{ padding:20px 36px 40px; overflow-y:auto; }

  .table-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    overflow:hidden;
  }
  table{
    width:100%;
    border-collapse:collapse;
  }
  thead th{
    text-align:left;
    font-size:11.5px;
    letter-spacing:0.04em;
    color:var(--ink-soft);
    font-weight:500;
    padding:14px 20px;
    background:#FBFAF6;
    border-bottom:1px solid var(--line);
  }
  tbody td{
    padding:16px 20px;
    font-size:13.5px;
    color:var(--ink);
    border-bottom:1px solid var(--line);
    vertical-align:middle;
  }
  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:#FBFAF6; }

  .event-title{
    font-weight:500;
    color:var(--ink);
  }
  .event-sub{
    font-size:11.5px;
    color:#9A978C;
    margin-top:3px;
  }

  .date-badge{
    display:flex;
    flex-direction:column;
    line-height:1.4;
  }
  .date-badge .d{ font-weight:500; }
  .date-badge .t{ font-size:11.5px; color:#9A978C; }

  .capacity{
    display:flex;
    flex-direction:column;
    gap:6px;
    min-width:110px;
  }
  .capacity-text{ font-size:12px; color:var(--ink-soft); }
  .capacity-bar{
    height:5px;
    background:var(--line);
    border-radius:3px;
    overflow:hidden;
  }
  .capacity-bar-fill{
    height:100%;
    background:var(--accent-deep);
    border-radius:3px;
  }

  .status-badge{
    display:inline-block;
    font-size:11px;
    padding:4px 11px;
    border-radius:20px;
    font-weight:500;
  }
  .status-badge.open{ background:var(--status-open-bg); color:var(--status-open-text); }
  .status-badge.closed{ background:var(--status-closed-bg); color:var(--status-closed-text); }
  .status-badge.ended{ background:var(--status-ended-bg); color:var(--status-ended-text); }

  .row-actions{
    display:flex;
    gap:6px;
    justify-content:flex-end;
  }
  .icon-btn{
    width:32px; height:32px;
    display:flex; align-items:center; justify-content:center;
    border-radius:6px;
    border:1px solid var(--line);
    background:var(--card);
    color:var(--ink-soft);
    cursor:pointer;
    transition: background 0.15s ease, color 0.15s ease;
  }
  .icon-btn:hover{ background:#F1EEE6; color:var(--ink); }
  .icon-btn.danger:hover{ color:var(--danger); border-color:var(--danger); }
  .icon-btn svg{ width:15px; height:15px; }

  .pagination{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:18px 4px 0;
    font-size:12.5px;
    color:var(--ink-soft);
  }
  .page-btns{ display:flex; gap:6px; }
  .page-btn{
    width:30px; height:30px;
    display:flex; align-items:center; justify-content:center;
    border-radius:6px;
    border:1px solid var(--line);
    background:var(--card);
    font-size:12.5px;
    color:var(--ink-soft);
    cursor:pointer;
  }
  .page-btn.active{
    background:var(--panel);
    color:#F4F1E8;
    border-color:var(--panel);
  }
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
      <div class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M7 3h10a1 1 0 0 1 1 1v16l-3-2-2 2-2-2-2 2-3-2V4a1 1 0 0 1 1-1Z"/><path d="M9 8h6M9 12h6"/></svg>
        回覧
        <span class="count">12</span>
      </div>
      <div class="nav-item active">
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
      <div>
        <h1>イベント管理</h1>
        <div class="desc">地域イベントの作成・参加受付状況を管理します</div>
      </div>
      <a href="{{ route('admin.events.create') }}" class="btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
          新規イベント作成
      </a>
    </header>

    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-label">開催予定</div>
        <div class="stat-value">{{ $upcomingCount }}<span>件</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">受付中の参加申込</div>
        <div class="stat-value">{{ $openParticipantsCount }}<span>名</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">今月の開催実績</div>
        <div class="stat-value">{{ $endedThisMonthCount }}<span>件</span></div>
      </div>
    </div>

    <div class="toolbar">
      <div class="tabs">
        <a href="{{ route('admin.events.index') }}" class="tab {{ request('status') === null ? 'active' : '' }}">すべて</a>
        <a href="{{ route('admin.events.index', ['status' => 'open']) }}" class="tab {{ request('status') === 'open' ? 'active' : '' }}">受付中</a>
        <a href="{{ route('admin.events.index', ['status' => 'closed']) }}" class="tab {{ request('status') === 'closed' ? 'active' : '' }}">受付終了</a>
        <a href="{{ route('admin.events.index', ['status' => 'ended']) }}" class="tab {{ request('status') === 'ended' ? 'active' : '' }}">開催終了</a>
      </div>
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="text" placeholder="イベントを検索">
      </div>
    </div>

    <div class="content">
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th style="width:32%">イベント名</th>
              <th>開催日時</th>
              <th>会場</th>
              <th>参加状況</th>
              <th>ステータス</th>
              <th style="text-align:right">操作</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($events as $event)
              <tr>
                <td>
                  <div class="event-title">{{ $event->title }}</div>
                  <div class="event-sub">{{ $event->category }}</div>
                </td>
                <td>
                  <div class="date-badge">
                    <span class="d">{{ $event->start_at->format('Y/m/d') }}</span>
                    <span class="t">{{ $event->start_at->format('H:i') }}〜{{ $event->end_at?->format('H:i') }}</span>
                  </div>
                </td>
                <td>{{ $event->venue }}</td>
                <td>
                  <div class="capacity">
                    <span class="capacity-text">{{ $event->participants_count }} / {{ $event->capacity }}名</span>
                    <div class="capacity-bar">
                      <div class="capacity-bar-fill" style="width:{{ $event->capacity ? min(100, round($event->participants_count / $event->capacity * 100)) : 0 }}%"></div>
                    </div>
                  </div>
                </td>
                <td>
                  @if ($event->status === 'open')
                    <span class="status-badge open">受付中</span>
                  @elseif ($event->status === 'closed')
                    <span class="status-badge closed">受付終了</span>
                  @else
                    <span class="status-badge ended">開催終了</span>
                  @endif
                </td>
                <td>
                  <div class="row-actions">
                    <a class="icon-btn" href="{{ route('admin.events.show', $event->id) }}" title="参加状況">...</a>
                    <a class="icon-btn" href="{{ route('admin.events.edit', $event->id) }}" title="編集">...</a>
                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('このイベントを削除しますか？');">
                      @csrf @method('DELETE')
                      <button type="submit" class="icon-btn danger" title="削除">...</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="text-align:center; color:var(--ink-soft); padding:40px 0;">イベントがありません</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">
        <span>全{{ $events->total() }}件中 {{ $events->firstItem() }}〜{{ $events->lastItem() }}件を表示</span>
        {{ $events->links() }}
      </div>
    </div>
  </div>
</div>
</body>
</html>