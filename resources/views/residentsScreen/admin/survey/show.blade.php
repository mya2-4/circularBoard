@extends('layouts.app1')

@section('title', $survey->title)
@section('description', '回答状況の詳細')

@section('header-actions')
    <a href="{{ route('admin.surveys.edit', $survey->id) }}" class="btn-cancel">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M4 20h4L18 10a2.8 2.8 0 0 0-4-4L4 16v4Z"/></svg>
        編集
    </a>
    <a href="{{ route('admin.survey.index') }}" class="btn-cancel">一覧へ戻る</a>
@endsection

@push('styles')
<style>
  .stats-row{ display:flex; gap:16px; margin-bottom:24px; }
  .stat-card{
    flex:1; background:var(--card); border:1px solid var(--line);
    border-radius:10px; padding:18px 20px;
  }
  .stat-label{ font-size:11.5px; color:var(--ink-soft); margin-bottom:8px; }
  .stat-value{
    font-family:'Noto Serif JP', serif; font-size:26px; font-weight:500; color:var(--ink);
  }
  .stat-value span{
    font-size:13px; font-family:'Noto Sans JP', sans-serif;
    color:var(--ink-soft); font-weight:400; margin-left:4px;
  }

  .meta-row{
    display:flex; gap:20px; align-items:center; margin-bottom:24px;
    font-size:12.5px; color:var(--ink-soft);
  }
  .status-badge{
    display:inline-block; font-size:11px; padding:4px 11px;
    border-radius:20px; font-weight:500;
  }
  .status-badge.open{ background:#EAF1EC; color:#4E6B5A; }
  .status-badge.draft{ background:#F1EEE6; color:#8A7A5C; }
  .status-badge.closed{ background:#EFEBE6; color:#9A978C; }

  .question-card{
    background:var(--card); border:1px solid var(--line);
    border-radius:10px; padding:22px 24px; margin-bottom:16px;
  }
  .question-head{
    display:flex; align-items:center; gap:10px; margin-bottom:16px;
  }
  .question-index{
    font-size:11.5px; font-weight:700; color:var(--ink-soft);
  }
  .question-body{
    font-size:14.5px; font-weight:500; color:var(--ink);
  }
  .question-type{
    font-size:11px; color:var(--ink-soft);
    border:1px solid var(--line); border-radius:12px;
    padding:2px 9px; margin-left:auto;
  }

  .option-result{ margin-bottom:12px; }
  .option-result:last-child{ margin-bottom:0; }
  .option-result-head{
    display:flex; justify-content:space-between;
    font-size:12.5px; color:var(--ink); margin-bottom:6px;
  }
  .option-result-count{ color:var(--ink-soft); }
  .option-bar{
    height:8px; background:var(--line); border-radius:4px; overflow:hidden;
  }
  .option-bar-fill{ height:100%; background:var(--accent-deep); border-radius:4px; }

  .text-answers{ display:flex; flex-direction:column; gap:10px; }
  .text-answer{
    background:#FBFAF6; border:1px solid var(--line);
    border-radius:8px; padding:12px 14px;
    font-size:13px; color:var(--ink); line-height:1.6;
  }
  .no-answers{ font-size:12.5px; color:var(--ink-soft); padding:8px 0; }
</style>
@endpush

@section('content')

  <div class="meta-row">
    @if ($survey->status === 'open')
      <span class="status-badge open">受付中</span>
    @else ($survey->status === 'draft')
      <span class="status-badge draft">下書き</span>
    @endif
    <span>{{ $survey->category }}</span>
    <span>
      @if ($survey->starts_at)
        {{ $survey->starts_at->format('Y/m/d') }}〜{{ $survey->ends_at?->format('Y/m/d') }}
      @else
        期間未設定
      @endif
    </span>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-label">総回答数</div>
      <div class="stat-value">{{ $responsesCount }}<span>件</span></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">対象人数</div>
      <div class="stat-value">{{ $survey->target_count ?? '—' }}<span>{{ $survey->target_count ? '名' : '' }}</span></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">回答率</div>
      <div class="stat-value">{{ $survey->response_rate !== null ? $survey->response_rate : '—' }}<span>%</span></div>
    </div>
  </div>

  @foreach ($survey->questions as $index => $question)
    <div class="question-card">
      <div class="question-head">
        <span class="question-index">Q{{ $index + 1 }}</span>
        <span class="question-body">{{ $question->body }}</span>
        <span class="question-type">
          @if ($question->type === 'text') 自由記述
          @elseif ($question->type === 'single_choice') 単一選択
          @else 複数選択
          @endif
        </span>
      </div>

      @if ($question->type === 'text')
        <div class="text-answers">
          @forelse ($textAnswers->get($question->id, collect()) as $answer)
            <div class="text-answer">{{ $answer->answer_text }}</div>
          @empty
            <div class="no-answers">まだ回答がありません</div>
          @endforelse
        </div>
      @else
        @php
          $totalSelections = $question->options->sum('selections_count');
        @endphp
        @forelse ($question->options as $option)
          @php
            $rate = $totalSelections > 0
              ? round($option->selections_count / $totalSelections * 100)
              : 0;
          @endphp
          <div class="option-result">
            <div class="option-result-head">
              <span>{{ $option->label }}</span>
              <span class="option-result-count">{{ $option->selections_count }}件（{{ $rate }}%）</span>
            </div>
            <div class="option-bar">
              <div class="option-bar-fill" style="width:{{ $rate }}%"></div>
            </div>
          </div>
        @empty
          <div class="no-answers">選択肢が設定されていません</div>
        @endforelse
      @endif
    </div>
  @endforeach

@endsection