@extends('layouts.app')

@section('title', $event->title)

@section('content')

<style>
  .event-detail{
    max-width:640px;
  }
  .back-link{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:13px;
    color:var(--ink-soft);
    text-decoration:none;
    margin-bottom:18px;
  }
  .back-link:hover{ color:var(--ink); }
  .back-link svg{ width:15px; height:15px; }

  .event-detail h1{
    font-family:'Noto Serif JP', serif;
    font-size:22px;
    font-weight:500;
    margin:0 0 10px;
    color:var(--ink);
  }
  .status-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:11px;
    padding:4px 12px;
    border-radius:20px;
    font-weight:700;
    letter-spacing:0.03em;
    margin-bottom:16px;
  }
  .status-badge.open{ background:#EAF1EC; color:#4E6B5A; }
  .status-badge.closed{ background:#F1EEE6; color:#8A7A5C; }
  .status-badge.ended{ background:#EFEBE6; color:#9A978C; }

  .event-info{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    padding:20px 22px;
    margin-bottom:20px;
    font-size:14px;
    color:var(--ink-soft);
    line-height:2;
  }
  .event-info .label{
    display:inline-block;
    width:88px;
    color:#9A978C;
    font-size:12.5px;
  }

  .event-body{
    font-size:14px;
    line-height:1.9;
    color:var(--ink-soft);
    margin-bottom:26px;
  }

  .alert{
    padding:12px 16px;
    border-radius:6px;
    font-size:13px;
    margin-bottom:20px;
  }
  .alert-success{ background:#EAF1EC; color:#4E6B5A; }
  .alert-error{ background:#F7ECE8; color:#A9673B; }

  .participate-box{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    padding:22px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
  }
  .participate-box .msg-title{
    font-size:14.5px;
    font-weight:500;
    color:var(--ink);
    margin-bottom:4px;
  }
  .participate-box .msg-desc{
    font-size:12.5px;
    color:var(--ink-soft);
  }
  .btn-join{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:11px 20px;
    background:var(--panel);
    color:#F4F1E8;
    border:none;
    border-radius:6px;
    font-size:13.5px;
    font-weight:700;
    cursor:pointer;
    white-space:nowrap;
  }
  .btn-join:hover{ background:var(--panel-soft); }
  .btn-join:disabled{
    background:#B9B6AA;
    cursor:not-allowed;
  }

  .joined-notice{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:13.5px;
    color:var(--accent-deep);
    font-weight:500;
  }
  .joined-notice svg{ width:17px; height:17px; }

  .participate-box{
  flex-direction: column;
  align-items: stretch;
  }
  .participate-form .field{ margin-bottom: 16px; }
  .participate-form label{
    display:block;
    font-size:12.5px;
    font-weight:500;
    color:var(--ink);
    margin-bottom:6px;
  }
  .participate-form input,
  .participate-form textarea{
    width:100%;
    padding:10px 12px;
    font-size:13.5px;
    font-family:inherit;
    border:1px solid var(--line);
    border-radius:6px;
    outline:none;
  }
  .participate-form textarea{ resize:vertical; }
  .participate-form input:focus,
  .participate-form textarea:focus{ border-color: var(--accent-deep); }
  .error-text{
    font-size:11.5px;
    color:#A9673B;
    margin-top:6px;
  }
</style>

<div class="content">
  <div class="event-detail">
    <a href="{{ route('events.index') }}" class="back-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      イベント一覧に戻る
    </a>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <h1>{{ $event->title }}</h1>

    @if ($event->status === 'open')
      <span class="status-badge open">受付中</span>
    @elseif ($event->status === 'closed')
      <span class="status-badge closed">受付終了</span>
    @else
      <span class="status-badge ended">開催終了</span>
    @endif

    <div class="event-info">
      <div><span class="label">カテゴリ</span>{{ $event->category ?? '—' }}</div>
      <div><span class="label">開催日時</span>{{ $event->start_at->format('Y年n月j日') }} {{ $event->start_at->format('H:i') }}〜{{ $event->end_at?->format('H:i') }}</div>
      <div><span class="label">会場</span>{{ $event->venue ?? '未定' }}</div>
      @if ($event->capacity)
        <div><span class="label">定員</span>{{ $event->participants_sum_participant_count ?? 0 }} / {{ $event->capacity }}名</div>
      @else
        <div><span class="label">参加者数</span>{{ $event->participants_sum_participant_count ?? 0 }}名</div>
      @endif
    </div>

    @if ($event->status === 'open')
    <div class="participate-box">
      @if ($myParticipation)
        <div class="joined-notice">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m5 13 4 4L19 7"/></svg>
          参加申込済みです（{{ $myParticipation->participant_count }}名）
          変更や確認事項があれば責任者に連絡してください。
        </div>
      @else
        <form action="{{ route('events.participate', $event->id) }}" method="POST" class="participate-form">
          @csrf

          <div class="field">
            <label for="participant_count">参加人数 <span style="color:var(--danger, #A9673B)">*</span></label>
            <input type="number" id="participant_count" name="participant_count" min="1" max="20"
                  value="{{ old('participant_count', 1) }}">
            @error('participant_count') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <div class="field">
            <label for="remarks">連絡事項（任意）</label>
            <textarea id="remarks" name="remarks" rows="3" placeholder="アレルギーの有無、送迎の要否など">{{ old('remarks') }}</textarea>
            @error('remarks') <div class="error-text">{{ $message }}</div> @enderror
          </div>

          <button type="submit" class="btn-join">参加する</button>
        </form>
      @endif
    </div>
  @endif
  </div>
</div>

@endsection