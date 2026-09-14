@extends('layouts.app1')

@section('title', '新規投稿作成')

@section('breadcrumb')
    <a href="{{ route('admin.posts.index') }}">回覧管理</a>
    <span>／</span>
    <span>新規作成</span>
@endsection

@push('styles')
<style>
  .post-form{
    display:flex;
    gap:24px;
    align-items:flex-start;
  }

  .form-col{ flex:1; min-width:0; display:flex; flex-direction:column; gap:20px; }
  .card{
    background:var(--card); border:1px solid var(--line); border-radius:10px; padding:26px 28px;
  }
  .card-title{
    font-family:'Noto Serif JP', serif; font-size:15px; font-weight:500; color:var(--ink);
    margin:0 0 20px; padding-bottom:14px; border-bottom:1px solid var(--line);
  }

  .field{ margin-bottom:20px; }
  .field:last-child{ margin-bottom:0; }
  .field label{
    display:flex; align-items:center; gap:6px;
    font-size:12.5px; color:var(--ink-soft); margin-bottom:8px; letter-spacing:0.02em;
  }
  .field label .required{
    font-size:10px; color:var(--danger); border:1px solid var(--danger);
    padding:1px 6px; border-radius:20px; font-weight:700;
  }
  .field input[type=text], .field input[type=date], .field input[type=time], .field select, .field textarea{
    width:100%; padding:12px 14px; font-size:14px; font-family:inherit; color:var(--ink);
    background:var(--card); border:1px solid var(--line); border-radius:6px; outline:none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease; appearance:none;
  }
  .field input::placeholder, .field textarea::placeholder{ color:#B9B6AA; }
  .field input:focus, .field select:focus, .field textarea:focus{
    border-color: var(--accent-deep); box-shadow: 0 0 0 3px rgba(78,107,90,0.12);
  }
  .field textarea{ resize:vertical; min-height:220px; line-height:1.8; }
  .field .hint{ font-size:11.5px; color:#9A978C; margin-top:6px; }
  .field .error{ font-size:11.5px; color:var(--danger); margin-top:6px; }
  .field input.is-invalid, .field select.is-invalid, .field textarea.is-invalid{
    border-color: var(--danger);
  }
  .field-row{ display:flex; gap:14px; }
  .field-row .field{ flex:1; }

  .upload-box{
    border:1.5px dashed var(--line); border-radius:8px; padding:32px 20px;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:10px; color:var(--ink-soft); cursor:pointer; text-align:center;
    transition: border-color 0.15s ease, background 0.15s ease;
    position:relative;
  }
  .upload-box:hover{ border-color: var(--accent-deep); background:#FBFAF6; }
  .upload-box svg{ width:26px; height:26px; color:#9A978C; }
  .upload-box .main-text{ font-size:13px; color:var(--ink); }
  .upload-box .sub-text{ font-size:11.5px; color:#9A978C; }
  .upload-box input[type=file]{
    position:absolute; inset:0; opacity:0; cursor:pointer;
  }

  .side-col{ width:320px; flex-shrink:0; display:flex; flex-direction:column; gap:20px; }

  .radio-group{ display:flex; flex-direction:column; gap:10px; }
  .radio-option{
    display:flex; align-items:flex-start; gap:10px; padding:12px 14px;
    border:1px solid var(--line); border-radius:8px; cursor:pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
  }
  .radio-option:hover{ background:#FBFAF6; }
  .radio-option input{ margin-top:3px; accent-color: var(--accent-deep); flex-shrink:0; }
  .radio-option .opt-title{ font-size:13.5px; font-weight:500; color:var(--ink); }
  .radio-option .opt-desc{ font-size:11.5px; color:var(--ink-soft); margin-top:2px; line-height:1.5; }
  .radio-option:has(input:checked){ border-color: var(--accent-deep); background:#F3F6F4; }

  .checkbox-row{
    display:flex; align-items:flex-start; gap:10px; padding:12px 14px;
    border:1px solid var(--line); border-radius:8px; margin-top:14px;
  }
  .checkbox-row input{ margin-top:3px; accent-color: var(--accent-deep); flex-shrink:0; }
  .checkbox-row .opt-title{ font-size:13.5px; font-weight:500; color:var(--ink); }
  .checkbox-row .opt-desc{ font-size:11.5px; color:var(--ink-soft); margin-top:2px; line-height:1.5; }

  .region-list{ display:flex; flex-direction:column; gap:8px; max-height:220px; overflow-y:auto; padding-right:4px; }
  .region-item{
    display:flex; align-items:center; gap:10px; padding:9px 12px;
    border:1px solid var(--line); border-radius:6px; font-size:13px; color:var(--ink);
  }
  .region-item input{ accent-color: var(--accent-deep); }
  .region-count{
    margin-top:12px; font-size:12px; color:var(--ink-soft);
    padding-top:12px; border-top:1px solid var(--line);
  }
  .region-count b{ color:var(--ink); font-family:'Noto Serif JP', serif; font-size:16px; margin-left:4px; }

  .alert{
    padding:14px 18px; border-radius:8px; font-size:13px; margin-bottom:20px;
  }
  .alert-error{ background:#F7ECE8; color:#A9673B; border:1px solid #EAD3C7; }
</style>
@endpush

@section('content')

    @if ($errors->any())
        <div class="alert alert-error">
            入力内容にエラーがあります。各項目をご確認ください。
        </div>
    @endif

    <form class="post-form" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-col">
            <div class="card">
                <h2 class="card-title">投稿内容</h2>

                <div class="field">
                    <label for="title">タイトル <span class="required">必須</span></label>
                    <input id="title" name="title" type="text"
                           class="@error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           placeholder="例：秋祭り開催のお知らせ">
                    @error('title')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="category">カテゴリ <span class="required">必須</span></label>
                        <select id="category" name="category" class="@error('category') is-invalid @enderror">
                            <option value="">選択してください</option>
                            <option value="event"       {{ old('category') === 'event'       ? 'selected' : '' }}>地域イベント</option>
                            <option value="notice"      {{ old('category') === 'notice'      ? 'selected' : '' }}>お知らせ</option>
                            <option value="disaster"    {{ old('category') === 'disaster'    ? 'selected' : '' }}>防災・安全</option>
                            <option value="environment" {{ old('category') === 'environment' ? 'selected' : '' }}>生活環境</option>
                        </select>
                        @error('category')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label for="summary">一覧表示用の要約</label>
                        <input id="summary" name="summary" type="text"
                               value="{{ old('summary') }}"
                               placeholder="一覧に表示される短い説明文">
                    </div>
                </div>

                <div class="field">
                    <label for="body">本文 <span class="required">必須</span></label>
                    <textarea id="body" name="body"
                              class="@error('body') is-invalid @enderror"
                              placeholder="地域の皆さまへお伝えしたい内容を入力してください">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card">
                <h2 class="card-title">画像・添付ファイル</h2>
                <label class="upload-box">
                    <input type="file" name="images[]" accept="image/png, image/jpeg" multiple>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 15V3m0 12-4-4m4 4 4-4M4 17v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                    <div class="main-text">クリックして画像をアップロード、またはドラッグ＆ドロップ</div>
                    <div class="sub-text">JPG・PNG（最大5MB、最大4枚まで）</div>
                </label>
                @error('images')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="side-col">
            <div class="card">
                <h2 class="card-title">公開設定</h2>

                <div class="field">
                    <label for="status">公開状態 <span class="required">必須</span></label>
                    <select id="status" name="status" class="@error('status') is-invalid @enderror">
                        <option value="draft"  {{ old('status', 'draft') === 'draft'  ? 'selected' : '' }}>下書き保存</option>
                        <option value="public" {{ old('status') === 'public' ? 'selected' : '' }}>今すぐ公開する</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="published_at_date">公開日</label>
                    <input id="published_at_date" name="published_at_date" type="date"
                           value="{{ old('published_at_date', now()->format('Y-m-d')) }}">
                </div>

                <div class="field">
                    <label for="published_at_time">公開時刻</label>
                    <input id="published_at_time" name="published_at_time" type="time"
                           value="{{ old('published_at_time', '09:00') }}">
                </div>
            </div>

            <div class="card">
                <h2 class="card-title">既読・確認設定</h2>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="read_mode" value="read_only"
                               {{ old('read_mode', 'read_only') === 'read_only' ? 'checked' : '' }}>
                        <div>
                            <div class="opt-title">閲覧のみ記録</div>
                            <div class="opt-desc">投稿を開いた時点で「既読」として記録します。</div>
                        </div>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="read_mode" value="confirmation_required"
                               {{ old('read_mode') === 'confirmation_required' ? 'checked' : '' }}>
                        <div>
                            <div class="opt-title">確認ボタンを必須にする</div>
                            <div class="opt-desc">住民が「確認しました」ボタンを押すまで「未確認」として扱います。</div>
                        </div>
                    </label>
                </div>
                <label class="checkbox-row">
                    <input type="checkbox" name="send_reminder" value="1"
                           {{ old('send_reminder', true) ? 'checked' : '' }}>
                    <div>
                        <div class="opt-title">未確認者へリマインド通知を送る</div>
                        <div class="opt-desc">公開から3日後、未確認の住民に自動で通知します。</div>
                    </div>
                </label>
            </div>

            <div class="card">
                <h2 class="card-title">配信対象地域</h2>
                <div class="region-list">
                    @foreach (($regions ?? []) as $region)
                        <label class="region-item">
                            <input type="checkbox" name="regions[]" value="{{ $region->id }}"
                                   {{ in_array($region->id, old('regions', [])) ? 'checked' : '' }}>
                            {{ $region->name }}
                        </label>
                    @endforeach
                </div>
                <div class="region-count">配信対象世帯数 <b>{{ $householdCount ?? '—' }}</b> 世帯</div>
            </div>

            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 13 4 4L19 7"/></svg>
                保存する
            </button>
        </div>
    </form>

@endsection