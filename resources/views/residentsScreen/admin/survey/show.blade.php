@extends('layouts.app1')

@section('title', '回答状況')
@section('description', $survey->title)

@section('breadcrumb')
    <a href="{{ route('admin.surveys.index') }}">アンケート管理</a>
    <span>／</span>
    <span>回答状況</span>
@endsection

@push('styles')
<style>
  .stats-row{
    display:flex;
    gap:16px;
    margin-bottom:24px;
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

  .question-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    padding:22px 24px;
    margin-bottom:16px;
  }
  .question-num{
    font-size:11px;
    color:var(--accent-deep);
    font-weight:700;
    letter-spacing:0.04em;
    margin-bottom:6px;
  }
  .question-body{
    font-size:15px;
    font-weight:500;
    color:var(--ink);
    margin-bottom:18px;
  }

  .option-result{ margin-bottom:14px; }
  .option-result:last-child{ margin-bottom:0; }
  .option-result-head{
    display:flex;
    justify-content:space-between;
    font-size:13px;
    color:var(--ink);
    margin-bottom:6px;
  }
  .option-result-head .count{ color:var(--ink-soft); font-size:12px; }
  .option-bar{
    height:8px;
    background:var(--line);
    border-radius:4px;
    overflow:hidden;
  }
  .option-bar-fill{
    height:100%;
    background:var(--accent-deep);
    border-radius:4px;
  }

  .text-answer-list{
    display:flex;
    flex-direction:column;
    gap:10px;
  }
  .text-answer{
    background:#FBFAF6;
    border:1px solid var(--line);
    border-radius:8px;
    padding:12px 14px;
  }
  .text-answer .who{
    font-size:11.5px;
    color:#9A978C;
    margin-bottom:4px;
  }
  .text-answer .body{
    font-size:13.5px;
    color:var(--ink);
    line-height:1.7;
    white-space:pre-wrap;
  }
  .no-answers{
    font-size:13px;
    color:var(--ink-soft);
    padding:12px 0;
  }

  .back-link{
    display:inline-flex; align-items:center; gap:6px;
    margin-top:24px; font-size:13px; color:var(--ink-soft); text-decoration:none;
  }
  .back-link:hover{ color:var(--ink); }
  .back-link svg{ width:14px; height:14px; }
</style>
@endpush

@section('content')

    <div class="stats-row">
      <div class="stat-card highlight">
        <div class="stat-label">回答数</div>
        <div class="stat-value">{{ $responsesCount }}<span>{{ $survey->target_count ? '/ ' . $survey->target_count . '名' : '名' }}</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">質問数</div>
        <div class="stat-value">{{ $survey->questions->count() }}<span>問</span></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">実施期間</div>
        <div class="stat-value" style="font-size:15px;">
          @if ($survey->starts_at)
            {{ $survey->starts_at->format('n/j') }}〜{{ $survey->ends_at?->format('n/j') ?? '未定' }}
          @else
            未設定
          @endif
        </div>
      </div>
    </div>

    @foreach ($survey->questions as $question)
      <div class="question-card">
        <div class="question-num">質問 {{ $loop->iteration }}</div>
        <div class="question-body">{{ $question->body }}</div>

        @if ($question->type === 'text')
          @php $answers = $textAnswers->get($question->id, collect()); @endphp
          @if ($answers->isEmpty())
            <div class="no-answers">まだ回答がありません</div>
          @else
            <div class="text-answer-list">
              @foreach ($answers as $answer)
                <div class="text-answer">
                  <div class="who">
                    {{ $answer->response->user->last_name ?? '' }} {{ $answer->response->user->first_name ?? '匿名' }}
                    ／{{ $answer->created_at->format('Y/m/d H:i') }}
                  </div>
                  <div class="body">{{ $answer->answer_text }}</div>
                </div>
              @endforeach
            </div>
          @endif
        @else
          @php
            $totalSelections = $question->options->sum('selections_count');
          @endphp
          @if ($totalSelections === 0)
            <div class="no-answers">まだ回答がありません</div>
          @else
            @foreach ($question->options as $option)
              @php
                $pct = $totalSelections > 0 ? round($option->selections_count / $totalSelections * 100) : 0;
              @endphp
              <div class="option-result">
                <div class="option-result-head">
                  <span>{{ $option->label }}</span>
                  <span class="count">{{ $option->selections_count }}件（{{ $pct }}%）</span>
                </div>
                <div class="option-bar">
                  <div class="option-bar-fill" style="width:{{ $pct }}%"></div>
                </div>
              </div>
            @endforeach
          @endif
        @endif
      </div>
    @endforeach

    <a href="{{ route('admin.surveys.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        アンケート管理に戻る
      </a>
@endsection