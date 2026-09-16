@extends('layouts.app1')

@section('title', '新規アンケート作成')
@section('description', '地域住民向けアンケートを新規作成します')

@push('styles')
<style>
  .form-card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:10px;
    padding:28px 32px;
    max-width:760px;
  }
  .field{ margin-bottom:22px; }
  .field label{
    display:block;
    font-size:12.5px;
    font-weight:500;
    color:var(--ink);
    margin-bottom:8px;
  }
  .field .hint{ font-size:11.5px; color:var(--ink-soft); margin-top:6px; }
  .field input[type="text"],
  .field input[type="date"],
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
  .field textarea:focus{ border-color: var(--accent-deep); }
  .row-2{ display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
  .row-3{ display:grid; grid-template-columns: 1fr 1fr 1fr; gap:16px; }
  .error-text{ font-size:11.5px; color:var(--danger); margin-top:6px; }

  .section-title{
    font-family:'Noto Serif JP', serif;
    font-size:16px;
    font-weight:500;
    color:var(--ink);
    margin:28px 0 14px;
    padding-top:20px;
    border-top:1px solid var(--line);
  }

  .question-block{
    border:1px solid var(--line);
    border-radius:10px;
    padding:20px 20px 16px;
    margin-bottom:16px;
    background:#FBFAF6;
    position:relative;
  }
  .question-block-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:14px;
  }
  .question-num{
    font-size:12.5px;
    font-weight:700;
    color:var(--ink-soft);
    letter-spacing:0.04em;
  }
  .btn-remove-question{
    font-size:11.5px;
    color:var(--danger);
    background:none;
    border:none;
    cursor:pointer;
    text-decoration:underline;
  }
  .type-select{ margin-bottom:14px; }

  .options-wrap{ margin-top:10px; }
  .option-row{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:8px;
  }
  .option-row input[type="text"]{ flex:1; }
  .btn-remove-option{
    width:30px; height:30px;
    flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    border:1px solid var(--line);
    background:var(--card);
    border-radius:6px;
    color:var(--ink-soft);
    cursor:pointer;
  }
  .btn-remove-option:hover{ color:var(--danger); border-color:var(--danger); }
  .btn-add-option{
    display:inline-flex; align-items:center; gap:6px;
    font-size:12px;
    color:var(--accent-deep);
    background:none;
    border:none;
    cursor:pointer;
    padding:6px 0;
  }

  .btn-add-question{
    display:inline-flex; align-items:center; gap:8px;
    padding:11px 18px;
    background:var(--card);
    color:var(--ink);
    border:1px dashed var(--line);
    border-radius:6px;
    font-size:13px;
    font-weight:500;
    cursor:pointer;
    width:100%;
    justify-content:center;
  }
  .btn-add-question:hover{ background:#FBFAF6; border-color:var(--accent-deep); }

  .form-actions{ display:flex; gap:10px; margin-top:28px; }
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
    <form action="{{ route('admin.surveys.store') }}" method="POST" id="survey-form">
      @csrf

      <div class="field">
        <label for="title">タイトル <span style="color:var(--danger)">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="例：公園リニューアルに関するアンケート">
        @error('title') <div class="error-text">{{ $message }}</div> @enderror
      </div>

      <div class="row-2">
        <div class="field">
          <label for="category">カテゴリ</label>
          <input type="text" id="category" name="category" value="{{ old('category') }}" placeholder="例：まちづくり">
          @error('category') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="target_count">対象人数</label>
          <input type="number" id="target_count" name="target_count" min="0" value="{{ old('target_count') }}" placeholder="例：300">
          <div class="hint">回答率の分母として使用します（未入力可）</div>
          @error('target_count') <div class="error-text">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row-3">
        <div class="field">
          <label for="starts_at">実施開始日</label>
          <input type="date" id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
          @error('starts_at') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="ends_at">締切日</label>
          <input type="date" id="ends_at" name="ends_at" value="{{ old('ends_at') }}">
          @error('ends_at') <div class="error-text">{{ $message }}</div> @enderror
        </div>
        <div class="field">
          <label for="status">ステータス <span style="color:var(--danger)">*</span></label>
          <select id="status" name="status">
            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>下書き</option>
            <option value="open" {{ old('status') === 'open' ? 'selected' : '' }}>受付中</option>
            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>締切済み</option>
          </select>
          @error('status') <div class="error-text">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="section-title">質問</div>

      <div id="questions-wrap"></div>

      <button type="button" class="btn-add-question" id="btn-add-question">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M12 5v14M5 12h14"/></svg>
        質問を追加
      </button>

      <div class="form-actions">
        <button type="submit" class="btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="m5 13 4 4L19 7"/></svg>
          作成する
        </button>
        <a href="{{ route('admin.surveys.index') }}" class="btn-cancel">キャンセル</a>
      </div>
    </form>
  </div>

  <template id="question-template">
    <div class="question-block" data-question>
      <div class="question-block-head">
        <span class="question-num" data-question-num></span>
        <button type="button" class="btn-remove-question" data-remove-question>削除</button>
      </div>

      <div class="field">
        <label>質問文 <span style="color:var(--danger)">*</span></label>
        <input type="text" data-field="body" placeholder="例：この公園をよく利用しますか？">
      </div>

      <div class="field type-select">
        <label>回答形式</label>
        <select data-field="type" data-type-select>
          <option value="text">自由記述</option>
          <option value="single_choice">単一選択</option>
          <option value="multiple_choice">複数選択</option>
        </select>
      </div>

      <div class="options-wrap" data-options-wrap style="display:none;">
        <div data-options-list></div>
        <button type="button" class="btn-add-option" data-add-option>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M12 5v14M5 12h14"/></svg>
          選択肢を追加
        </button>
      </div>
    </div>
  </template>

  <template id="option-template">
    <div class="option-row" data-option-row>
      <input type="text" data-field="option" placeholder="選択肢を入力">
      <button type="button" class="btn-remove-option" data-remove-option>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="14" height="14"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </button>
    </div>
  </template>

  <script>
    (function () {
      const questionsWrap = document.getElementById('questions-wrap');
      const questionTemplate = document.getElementById('question-template');
      const optionTemplate = document.getElementById('option-template');
      const form = document.getElementById('survey-form');
      let questionCount = 0;

      function renumberQuestions() {
        const blocks = questionsWrap.querySelectorAll('[data-question]');
        blocks.forEach((block, i) => {
          block.querySelector('[data-question-num]').textContent = '質問 ' + (i + 1);
        });
      }

      function addOption(optionsList) {
        const node = optionTemplate.content.cloneNode(true);
        optionsList.appendChild(node);
      }

      function addQuestion() {
        const node = questionTemplate.content.cloneNode(true);
        const block = node.querySelector('[data-question]');
        questionsWrap.appendChild(node);
        questionCount++;
        renumberQuestions();

        const typeSelect = block.querySelector('[data-type-select]');
        const optionsWrap = block.querySelector('[data-options-wrap]');
        const optionsList = block.querySelector('[data-options-list]');

        typeSelect.addEventListener('change', function () {
          if (this.value === 'text') {
            optionsWrap.style.display = 'none';
          } else {
            optionsWrap.style.display = 'block';
            if (optionsList.children.length === 0) {
              addOption(optionsList);
              addOption(optionsList);
            }
          }
        });

        block.querySelector('[data-add-option]').addEventListener('click', function () {
          addOption(optionsList);
        });

        optionsList.addEventListener('click', function (e) {
          const btn = e.target.closest('[data-remove-option]');
          if (btn) {
            btn.closest('[data-option-row]').remove();
          }
        });

        block.querySelector('[data-remove-question]').addEventListener('click', function () {
          block.remove();
          renumberQuestions();
        });
      }

      document.getElementById('btn-add-question').addEventListener('click', addQuestion);

      // 初期状態で1問追加
      addQuestion();

      // 送信時、data-field属性の値を name="questions[n][...]" に変換して送信用のhidden inputを生成
      form.addEventListener('submit', function () {
        const blocks = questionsWrap.querySelectorAll('[data-question]');
        blocks.forEach((block, qIndex) => {
          const bodyInput = block.querySelector('[data-field="body"]');
          bodyInput.name = `questions[${qIndex}][body]`;

          const typeSelect = block.querySelector('[data-field="type"]');
          typeSelect.name = `questions[${qIndex}][type]`;

          const optionInputs = block.querySelectorAll('[data-field="option"]');
          optionInputs.forEach((input, oIndex) => {
            input.name = `questions[${qIndex}][options][${oIndex}]`;
          });
        });
      });
    })();
  </script>

@endsection