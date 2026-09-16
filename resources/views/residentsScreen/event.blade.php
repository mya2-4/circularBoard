@extends('layouts.app')

@section('title', 'イベント')

@section('content')

<style>
  .event{
    padding:26px 0;
    border-bottom:1px solid var(--line);
  }
  .event:last-child{ border-bottom:none; }
  .event-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:6px;
  }
  .event-head-left{
    display:flex;
    align-items:center;
    gap:10px;
  }
  .event-head h2{
    font-family:'Noto Serif JP', serif;
    font-size:18px;
    font-weight:500;
    margin:0;
    color:var(--ink);
  }
  .new-badge{
    font-size:11px;
    color:var(--new-tag);
    border:1px solid var(--new-tag);
    padding:2px 8px;
    border-radius:20px;
    letter-spacing:0.05em;
    flex-shrink:0;
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
    flex-shrink:0;
  }
  .status-badge.open{ background:#EAF1EC; color:#4E6B5A; }
  .status-badge.closed{ background:#F1EEE6; color:#8A7A5C; }
  .status-badge.ended{ background:#EFEBE6; color:#9A978C; }

  .event-meta{
    font-size:12px;
    color:#9A978C;
    margin-bottom:10px;
  }
  .event p{
    margin:0;
    font-size:14px;
    line-height:1.9;
    color:var(--ink-soft);
    max-width:640px;
  }

  .no-posts{
    font-size:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#9A978C;
    padding:60px 0;
  }

  .event-head h2 a{ color:inherit; text-decoration:none; }
  .event-head h2 a:hover{ text-decoration:underline; }
</style>

<div class="content">
    @forelse ($events as $event)
        <div class="event">
            <div class="event-head">
                <div class="event-head-left">
                  <h2><a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a></h2>
                </div>
            </div>

            <div class="event-meta">
                {{ $event->start_at->format('Y年n月j日') }} {{ $event->start_at->format('H:i') }}〜{{ $event->end_at?->format('H:i') }}
                @if ($event->venue)
                    ／{{ $event->venue }}
                @endif
                @if ($event->capacity)
                    ／参加 {{ $event->participants_sum_participant_count ?? 0 }} / {{ $event->capacity }}名
                @endif
            </div>

            <p>{{ $event->category }}</p>
        </div>
    @empty
        <p class="no-posts">地域のイベントがありません</p>
    @endforelse
</div>

@endsection