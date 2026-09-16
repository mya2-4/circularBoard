@extends('layouts.app')

@section('title', 'アンケート')

@section('content')

<style>
  .survey{
    padding:26px 0;
    border-bottom:1px solid var(--line);
  }
  .survey:last-child{ border-bottom:none; }
  .survey-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:6px;
  }
  .survey-head h2{
    font-family:'Noto Serif JP', serif;
    font-size:18px;
    font-weight:500;
    margin:0;
    color:var(--ink);
  }
  .survey-head h2 a{ color:inherit; text-decoration:none; }
  .survey-head h2 a:hover{ text-decoration:underline; }

  .answered-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:11px;
    padding:4px 12px;
    border-radius:20px;
    font-weight:700;
    background:#EAF1EC;
    color:#4E6B5A;
  }
  .survey-meta{
    font-size:12px;
    color:#9A978C;
    margin-bottom:6px;
  }
  .survey p{
    margin:0;
    font-size:14px;
    color:var(--ink-soft);
  }
  .no-posts{
    font-size:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#9A978C;
    padding:60px 0;
  }
</style>

<div class="content">
    @forelse ($surveys as $survey)
        <div class="survey">
            <div class="survey-head">
                <h2><a href="{{ route('surveys.show', $survey->id) }}">{{ $survey->title }}</a></h2>
                @if ($survey->answered)
                    <span class="answered-badge">回答済み</span>
                @endif
            </div>
            <div class="survey-meta">
                @if ($survey->ends_at)
                    {{ $survey->ends_at->format('Y年n月j日') }} 締切
                @endif
                ／{{ $survey->questions_count }}問
            </div>
            <p>{{ $survey->category }}</p>
        </div>
    @empty
        <p class="no-posts">回答するアンケートがありません</p>
    @endforelse
</div>

@endsection