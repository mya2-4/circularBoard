@extends('layouts.app1')

@section('title', '回覧管理')
@section('description', '地域住民に配信する回覧・お知らせを管理します')

@section('header-actions')
    <a href="{{ route('admin.posts.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        新規作成
    </a>
@endsection

@push('styles')
<style>
  /* このページ固有のスタイル（一覧テーブル・タブ・検索ボックスなど） */
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
  tbody tr.clickable-row{ cursor:pointer; }
  tbody tr:hover{ background:#FBFAF6; }

  .post-title{
    font-weight:500;
    color:var(--ink);
  }
  .post-sub{
    font-size:11.5px;
    color:#9A978C;
    margin-top:3px;
  }

  .status-badge{
    display:inline-block;
    font-size:11px;
    padding:4px 11px;
    border-radius:20px;
    font-weight:500;
  }
  .status-badge.public{ background:#EAF1EC; color:#4E6B5A; }
  .status-badge.draft{ background:#F1EEE6; color:#8A7A5C; }

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
</style>
@endpush

@section('content')

    <div class="toolbar">
        <div class="tabs">
            <a href="{{ route('admin.posts.index') }}" class="tab {{ request('status') === null ? 'active' : '' }}">すべて</a>
            <a href="{{ route('admin.posts.index', ['status' => 'public']) }}" class="tab {{ request('status') === 'public' ? 'active' : '' }}">公開中</a>
            <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="tab {{ request('status') === 'draft' ? 'active' : '' }}">下書き</a>
        </div>
        <form class="search-box" method="GET" action="{{ route('admin.posts.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="回覧を検索">
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th style="width:44%">タイトル</th>
                    <th>ステータス</th>
                    <th>公開日</th>
                    <th>閲覧数</th>
                    <th style="text-align:right">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr class="clickable-row" onclick="window.location='{{ route('admin.posts.show', $post->id) }}'">
                        <td>
                            <div class="post-title">{{ $post->title }}</div>
                            <div class="post-sub">{{ $post->category_label }}</div>
                        </td>
                        <td>
                            @if ($post->status === 'public')
                                <span class="status-badge public">公開中</span>
                            @else
                                <span class="status-badge draft">下書き</span>
                            @endif
                        </td>
                        <td>{{ $post->published_at?->format('Y/m/d') ?? '—' }}</td>
                        <td>{{ $post->status === 'public' ? $post->views_count : '—' }}</td>
                        <td onclick="event.stopPropagation()">
                            <div class="row-actions">
                                <a class="icon-btn" href="{{ route('admin.posts.show', $post->id) }}" title="閲覧状況">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 5c-7 0-9.5 7-9.5 7s2.5 7 9.5 7 9.5-7 9.5-7-2.5-7-9.5-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a class="icon-btn" href="{{ route('admin.posts.edit', $post->id) }}" title="編集">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20h4L18 10a2.8 2.8 0 0 0-4-4L4 16v4Z"/></svg>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('この投稿を削除しますか？');">
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
                        <td colspan="5" style="text-align:center; color:var(--ink-soft); padding:40px 0;">
                            投稿がありません
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>全{{ $posts->total() }}件中 {{ $posts->firstItem() }}〜{{ $posts->lastItem() }}件を表示</span>
        {{ $posts->links() }}
    </div>

@endsection