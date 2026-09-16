@extends('layouts.app')

@section('title', $survey->title)

@section('content')

<style>
  .survey-detail{ max-width:640px; }
  .back-link{
    display:inline-flex; align-items:center; gap:6px;
    font-size:13px; color:var(--ink-soft); text-decoration:none; margin-bottom:18px;
  }
  .back-link:hover{ color:var(--ink); }
  .back-link svg{ width:15px; height:15px; }

  .survey-detail h1{
    font-family:'Noto Serif JP', serif; font-size:22px; font-weight:500;
    margin:0 0 20px; color:var(--ink);
  }

  .alert{ padding:12px 16px; border-radius:6px; font-size:13px; margin-bottom:20px; }
  .alert-success{ background:#EAF1EC; color:#4E6B5A; }
  .alert-error{ background:#F7ECE8; color:#A9673B; }

  .question-card{
    background:var(--card); border:1px solid var(--line); border-radius:10px;
    padding:20px 22px; margin-bottom:16px;
  }
  .question-title{ font-size:14.5px; font-weight:500; color:var(--ink); margin-bottom:14px; }
  .option-row{ display:flex; align-items:center; gap:8px; margin-bottom:10px; font-size:13.5px; color:var(--ink-soft); }
  .option-row input{ flex-shrink:0; }
  textarea.answer-text{
    width:100%; padding:10px 12px; font-size:13.5px; font-family:inherit;
    border:1px solid var(--line); border-radius:6px; resize:vertical; min-height:80px; outline:none;
  }
  textarea.answer-text:focus{ border-color: var(--accent-deep); }
  .error-text{ font-size:11.5px; color:#A9673B; margin-top:6px; }

  .btn-submit{
    display:inline-flex; align-items:center; gap:8px;
    padding:12px 22px; background:var(--panel); color:#F4F1E8;
    border:none; border-radius:6px; font-size:13.5px; font-weight:700; cursor:pointer;
  }
  .btn-submit:hover{ background:var(--panel-soft); }

  .answered-notice{
    display:flex; align-items:center; gap:8px;
    font-size:13.5px; color:var(--accent-deep); font-weight:500;
    background:var(--card); border:1px solid var(--line); border-radius:10px;
    padding:20px 22px;
  }
</style>

<div class="content">
  <div class="survey-detail">
    <a href="{{ route('surveys.index') }}" class="back-link">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      アンケート一覧に戻る
    </a>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <h1>{{ $survey->title }}</h1>

    @if ($alreadyAnswered)
      <div class="answered-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="17" height="17"><path d="m5 13 4 4L19 7"/></svg>
        このアンケートにはすでに回答済みです
      </div>
    @else
      <form action="{{ route('surveys.store', $survey->id) }}" method="POST">
        @csrf

        @foreach ($survey->questions as $question)
          <div class="question-card">
            <div class="question-title">{{ $loop->iteration }}. {{ $question->body }}</div>

            @if ($question->type === 'text')
              <textarea class="answer-text" name="answers[{{ $question->id }}]">{{ old("answers.{$question->id}") }}</textarea>
            @elseif ($question->type === 'single_choice')
              @foreach ($question->options as $option)
                <label class="option-row">
                  <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                    {{ old("answers.{$question->id}") == $option->id ? 'checked' : '' }}>
                  {{ $option->label }}
                </label>
              @endforeach
            @elseif ($question->type === 'multiple_choice')
              @foreach ($question->options as $option)
                <label class="option-row">
                  <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}"
                    {{ in_array($option->id, old("answers.{$question->id}", [])) ? 'checked' : '' }}>
                  {{ $option->label }}
                </label>
              @endforeach
            @endif

            @error("answers.{$question->id}") <div class="error-text">{{ $message }}</div> @enderror
          </div>
        @endforeach

        <button type="submit" class="btn-submit">回答を送信する</button>
      </form>
    @endif
  </div>
</div>

@endsection