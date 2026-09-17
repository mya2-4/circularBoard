@extends('layouts.app1')

@section('title', '参加状況')
@section('description', $event->title)

@section('breadcrumb')
    <a href="{{ route('admin.events.index') }}">イベント管理</a>
    <span>／</span>
    <span>参加状況</span>
@endsection

@push('styles')
<style>
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
  .stat-card.highlight{ border-color: var(--accent-deep); }
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
    padding:14px 20px;
    font-size:13.5px;
    color:var(--ink);
    border-bottom:1px solid var(--line);
    vertical-align:middle;
  }
  tbody tr:last-child td{ border-bottom:none; }
  tbody tr:hover{ background:#FBFAF6; }

  .user-cell{ display:flex; align-items:center; gap:10px; }
  .user-avatar{
    width:30px; height:30px; border-radius:50%;
    background:var(--line); color:var(--ink-soft);
    display:flex; align-items:center; justify-content:center;
    font-size:11.5px; font-weight:700; flex-shrink:0;
  }
  .user-name{ font-weight:500; }
  .user-sub{ font-size:11.5px; color:#9A978C; margin-top:2px; }

  .remarks-text{
    font-size:12.5px;
    color:var(--ink-soft);
    max-width:260px;
    white-space:pre-wrap;
  }
  .timestamp{ font-size:12.5px; color:var(--ink-soft); }

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
      <div class="stat-card highlight">
        <div class="stat-label">申込人数</div>
        <div class="stat-value">{{ $totalParticipants }}<span>{{ $event->capacity ? '/ ' . $event->capacity . '名' : '名' }}</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">申込件数（世帯数）</div>
        <div class="stat-value">{{ $participants->total() }}<span>件</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">開催日時</div>
        <div class="stat-value" style="font-size:16px;">{{ $event->start_at->format('Y/m/d H:i') }}</div>
      </div>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width:24%">申込者</th>
            <th>世帯・地域</th>
            <th>参加人数</th>
            <th>連絡事項</th>
            <th>申込日時</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($participants as $participant)
            <tr>
              <td>
                <div class="user-cell">
                  <div class="user-avatar">{{ mb_substr($participant->user->last_name ?? $participant->user->name, 0, 1) }}</div>
                  <div>
                    <div class="user-name">{{ $participant->user->last_name }} {{ $participant->user->first_name }}</div>
                    <div class="user-sub">{{ $participant->user->region2 }}</div>
                  </div>
                </div>
              </td>
              <td>{{ $participant->user->region }}</td>
              <td>{{ $participant->participant_count }}名</td>
              <td class="remarks-text">{{ $participant->remarks ?? '—' }}</td>
              <td class="timestamp">{{ $participant->created_at->format('m/d H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align:center; color:var(--ink-soft); padding:40px 0;">
                参加申込がありません
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <span>全{{ $participants->total() }}件中 {{ $participants->firstItem() }}〜{{ $participants->lastItem() }}件を表示</span>
      {{ $participants->links() }}
    </div>

@endsection