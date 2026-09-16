@extends('layouts.app1')

@section('title', '新規イベント作成')
@section('description', '地域イベントを新規作成します')

@push('styles')
<style>
  .form-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    padding:28px 32px;
    max-width:720px;
  }
  .field{ margin-bottom:22px; }
  .field label{
    display:block;
    font-size:12.5px;
    font-weight:500;
    color:var(--ink);
    margin-bottom:8px;
  }
  .field .hint{
    font-size:11.5px;
    color:var(--ink-soft);
    margin-top:6px;
  }
  .field input[type="text"],
  .field input[type="date"],
  .field input[type="time"],
  .field input[type="number"],
  .field select,
  .field textarea{
    width:100%;
    padding:10px 12px;
    font-size:13.5px;
    font-family:inherit;
    color:var(--ink);
    background:var(--card);
    border:1px solid var(--line);
    border-radius:6px;
    outline:none;
  }
  .field input:focus,
  .field select:focus,
  .field textarea:focus{
    border-color: var(--accent-deep);
  }
  .field textarea{ resize:vertical; min-height:90px; }
  .row-2{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:16px;
  }
  .row-3{
    display:grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap:16px;
  }
  .error-text{
    font-size:11.5px;
    color:var(--danger);
    margin-top:6px;
  }
  .form-actions{
    display:flex;
    gap:10px;
    margin-top:8px;
  }
  .btn-primary{
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 20px;
    background:var(--panel);
    color:#F4F1E8;
    border:none;
    border-radius:6px;
    font-size:13.5px;
    font-weight:700;
    cursor:pointer;
  }
  .btn-primary:hover{ background:var(--panel-soft); }
  .btn-cancel{
    display:inline-flex; align-items:center;
    padding:11px 20px;
    background:var(--card);
    color:var(--ink-soft);
    border:1px solid var(--line);
    border-radius:6px;
    font-size:13.5px;
    font-weight:500;
    cursor:pointer;
    text-decoration:none;
  }
  .btn-cancel:hover{ background:#FBFAF6; }
</style>
@endpush

@section('content')

  <div class="form-card">
    <form action="{{ route('admin.events.store') }}" method="POST">
      @csrf

      <div class="field">
        <label for="title">イベント名 <span style="color:var(--danger)">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="例：秋祭り2026">
        @error('title') <div class="error-text">{{ $message }}</div> @enderror
      </div>

      <div class="row-2">
        <div class="field">
          <label for="category">カテゴリ</label>
          <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="例：地域交流イベント">
          @error('category') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="venue">会場</label>
          <input type="text" id="venue" name="venue" value="{{ old('venue') }}" placeholder="例：名駅第一公園">
          @error('venue') <div class="error-text">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row-3">
        <div class="field">
          <label for="start_date">開催日 <span style="color:var(--danger)">*</span></label>
          <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
          @error('start_date') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="start_time">開始時刻 <span style="color:var(--danger)">*</span></label>
          <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}">
          @error('start_time') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="end_time">終了時刻</label>
          <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}">
          @error('end_time') <div class="error-text">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row-2">
        <div class="field">
          <label for="capacity">定員</label>
          <input type="number" id="capacity" name="capacity" min="0" value="{{ old('capacity') }}" placeholder="例：150">
          <div class="hint">未入力の場合、定員なしとして扱われます</div>
          @error('capacity') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="status">ステータス <span style="color:var(--danger)">*</span></label>
          <select id="status" name="status">
            <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>受付中</option>
            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>受付終了</option>
            <option value="ended" {{ old('status') === 'ended' ? 'selected' : '' }}>開催終了</option>
          </select>
          @error('status') <div class="error-text">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="m5 13 4 4L19 7"/></svg>
          作成する
        </button>
        <a href="{{ route('admin.events.index') }}" class="btn-cancel">キャンセル</a>
      </div>
    </form>
  </div>

@endsection