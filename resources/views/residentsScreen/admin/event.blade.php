@extends('layouts.app1')

@section('title', 'イベント管理')
@section('description', '地域イベントの作成・参加受付状況を管理します')

@section('header-actions')
    <a href="{{ route('admin.events.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        新規イベント作成
    </a>
@endsection

@push('styles')
<style>
  :root{
    --status-open-bg: #EAF1EC;
    --status-open-text: #4E6B5A;
    --status-closed-bg: #F1EEE6;
    --status-closed-text: #8A7A5C;
    --status-ended-bg: #EFEBE6;
    --status-ended-text: #9A978C;
  }

  /* Stat cards */
  .stats-row{
    display:flex;
    gap:16px;
    margin-bottom:20px;
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
    margin-bottom:20px;
  }
  .tabs{ display:flex; gap:0; }
  .tab{
    padding:10px 4px;
    margin-right:26px;
    font-size:13px;
    color:var(--ink-soft);
    border-bottom:2px solid transparent;
    cursor:pointer;
    text-decoration:none;
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

  .table-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    overflow:hidden;
  }
  table{ width:100%; border-collapse:collapse; }
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

  .event-title{ font-weight:500; color:var(--ink); }
  .event-sub{ font-size:11.5px; color:#9A978C; margin-top:3px; }

  .date-badge{ display:flex; flex-direction:column; line-height:1.4; }
  .date-badge .d{ font-weight:500; }
  .date-badge .t{ font-size:11.5px; color:#9A978C; }

  .capacity{ display:flex; flex-direction:column; gap:6px; min-width:110px; }
  .capacity-text{ font-size:12px; color:var(--ink-soft); }
  .capacity-bar{ height:5px; background:var(--line); border-radius:3px; overflow:hidden; }
  .capacity-bar-fill{ height:100%; background:var(--accent-deep); border-radius:3px; }

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

  .row-actions{ display:flex; gap:6px; justify-content:flex-end; }
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
</style>
@endpush

@section('content')

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
      <form class="search-box" method="GET" action="{{ route('admin.events.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="イベントを検索">
      </form>
    </div>

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
                  <a class="icon-btn" href="{{ route('admin.events.show', $event->id) }}" title="参加状況">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 5c-7 0-9.5 7-9.5 7s2.5 7 9.5 7 9.5-7 9.5-7-2.5-7-9.5-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>
                  <a class="icon-btn" href="{{ route('admin.events.edit', $event->id) }}" title="編集">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20h4L18 10a2.8 2.8 0 0 0-4-4L4 16v4Z"/></svg>
                  </a>
                  <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('このイベントを削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn danger" title="削除">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0-1 13a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1L6 7"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; color:var(--ink-soft); padding:40px 0;">
                イベントがありません
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <span>全{{ $events->total() }}件中 {{ $events->firstItem() }}〜{{ $events->lastItem() }}件を表示</span>
      {{ $events->links() }}
    </div>

@endsection