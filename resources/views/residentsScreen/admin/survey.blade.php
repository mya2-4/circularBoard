@extends('layouts.app1')

@section('title', 'アンケート管理')
@section('description', '地域住民向けアンケートの作成・回答状況を管理します')

@section('header-actions')
    <a href="{{ route('admin.surveys.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        新規アンケート作成
    </a>
@endsection

@push('styles')
<style>
  :root{
    --status-open-bg: #EAF1EC;
    --status-open-text: #4E6B5A;
    --status-draft-bg: #F1EEE6;
    --status-draft-text: #8A7A5C;
    --status-closed-bg: #EFEBE6;
    --status-closed-text: #9A978C;
  }

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
  .stat-label{ font-size:11.5px; color:var(--ink-soft); margin-bottom:8px; }
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

  .survey-title{ font-weight:500; color:var(--ink); }
  .survey-sub{ font-size:11.5px; color:#9A978C; margin-top:3px; }

  .period{ display:flex; flex-direction:column; line-height:1.4; font-size:12.5px; }
  .period .end{ color:#9A978C; font-size:11.5px; margin-top:2px; }

  .response-rate{ display:flex; flex-direction:column; gap:6px; min-width:120px; }
  .response-text{ font-size:12px; color:var(--ink-soft); }
  .response-bar{ height:5px; background:var(--line); border-radius:3px; overflow:hidden; }
  .response-bar-fill{ height:100%; background:var(--accent-deep); border-radius:3px; }

  .status-badge{
    display:inline-block;
    font-size:11px;
    padding:4px 11px;
    border-radius:20px;
    font-weight:500;
  }
  .status-badge.open{ background:var(--status-open-bg); color:var(--status-open-text); }
  .status-badge.draft{ background:var(--status-draft-bg); color:var(--status-draft-text); }
  .status-badge.closed{ background:var(--status-closed-bg); color:var(--status-closed-text); }

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
        <div class="stat-label">回答受付中</div>
        <div class="stat-value">{{ $openCount }}<span>件</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">今月の総回答数</div>
        <div class="stat-value">{{ $thisMonthResponsesCount }}<span>件</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">平均回答率</div>
        <div class="stat-value">{{ $avgResponseRate !== null ? round($avgResponseRate) : '—' }}<span>%</span></div>
      </div>
    </div>

    <div class="toolbar">
      <div class="tabs">
        <a href="{{ route('admin.surveys.index') }}" class="tab {{ request('status') === null ? 'active' : '' }}">すべて</a>
        <a href="{{ route('admin.surveys.index', ['status' => 'open']) }}" class="tab {{ request('status') === 'open' ? 'active' : '' }}">受付中</a>
        <a href="{{ route('admin.surveys.index', ['status' => 'draft']) }}" class="tab {{ request('status') === 'draft' ? 'active' : '' }}">下書き</a>
        <a href="{{ route('admin.surveys.index', ['status' => 'closed']) }}" class="tab {{ request('status') === 'closed' ? 'active' : '' }}">締切済み</a>
      </div>
      <form class="search-box" method="GET" action="{{ route('admin.surveys.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="アンケートを検索">
      </form>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width:30%">タイトル</th>
            <th>実施期間</th>
            <th>回答状況</th>
            <th>質問数</th>
            <th>ステータス</th>
            <th style="text-align:right">操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($surveys as $survey)
            <tr>
              <td>
                <div class="survey-title">{{ $survey->title }}</div>
                <div class="survey-sub">{{ $survey->category }}</div>
              </td>
              <td>
                <div class="period">
                  @if ($survey->starts_at)
                    {{ $survey->starts_at->format('Y/m/d') }}〜
                    <span class="end">{{ $survey->ends_at?->format('Y/m/d') }} 締切</span>
                  @else
                    未設定
                  @endif
                </div>
              </td>
              <td>
                <div class="response-rate">
                  @if ($survey->target_count)
                    <span class="response-text">{{ $survey->responses_count }} / {{ $survey->target_count }}名（{{ $survey->response_rate }}%）</span>
                    <div class="response-bar"><div class="response-bar-fill" style="width:{{ $survey->response_rate }}%"></div></div>
                  @else
                    <span class="response-text">—</span>
                    <div class="response-bar"><div class="response-bar-fill" style="width:0%"></div></div>
                  @endif
                </div>
              </td>
              <td>{{ $survey->questions_count }}問</td>
              <td>
                @if ($survey->status === 'open')
                  <span class="status-badge open">受付中</span>
                @elseif ($survey->status === 'draft')
                  <span class="status-badge draft">下書き</span>
                @else
                  <span class="status-badge closed">締切済み</span>
                @endif
              </td>
              <td>
                <div class="row-actions">
                  <a class="icon-btn" href="{{ route('admin.surveys.show', $survey->id) }}" title="回答状況">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 17v-6a3 3 0 0 1 6 0v6M5 21h14a1 1 0 0 0 1-1V10L12 3 4 10v10a1 1 0 0 0 1 1Z"/></svg>
                  </a>
                  <a class="icon-btn" href="{{ route('admin.surveys.edit', $survey->id) }}" title="編集">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20h4L18 10a2.8 2.8 0 0 0-4-4L4 16v4Z"/></svg>
                  </a>
                  <form action="{{ route('admin.surveys.destroy', $survey->id) }}" method="POST" onsubmit="return confirm('このアンケートを削除しますか？');">
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
                アンケートがありません
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <span>全{{ $surveys->total() }}件中 {{ $surveys->firstItem() }}〜{{ $surveys->lastItem() }}件を表示</span>
      {{ $surveys->links() }}
    </div>

@endsection