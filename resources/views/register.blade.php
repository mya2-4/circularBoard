<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>みるまち - 新規登録</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@500;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #F6F4EE;
    --panel: #1E2B25;
    --panel-soft: #263832;
    --accent: #7C9885;
    --accent-deep: #4E6B5A;
    --ink: #23241F;
    --ink-soft: #5B5A52;
    --line: #E2DFD3;
    --card: #FFFFFF;
  }
  *{box-sizing:border-box;}
  html,body{ height:100%; margin:0; }
  body{
    font-family:'Noto Sans JP', sans-serif;
    display:flex;
    min-height:100vh;
  }

  /* Left brand panel */
  .brand-panel{
    width:40%;
    background:var(--panel);
    color:#EDEAE1;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:56px;
    position:relative;
    overflow:hidden;
  }
  .brand-panel::after{
    content:"";
    position:absolute;
    right:-120px;
    bottom:-120px;
    width:360px;
    height:360px;
    border-radius:50%;
    border:1px solid rgba(255,255,255,0.08);
  }
  .brand-panel::before{
    content:"";
    position:absolute;
    right:-40px;
    bottom:-200px;
    width:360px;
    height:360px;
    border-radius:50%;
    border:1px solid rgba(255,255,255,0.06);
  }
  .brand-mark{
    font-family:'Noto Serif JP', serif;
    font-size:32px;
    font-weight:700;
    letter-spacing:0.04em;
    color:#F4F1E8;
  }
  .brand-sub{
    margin-top:8px;
    font-size:11px;
    letter-spacing:0.2em;
    color:var(--accent);
  }
  .brand-copy{
    position:relative;
    z-index:1;
  }
  .brand-copy h2{
    font-family:'Noto Serif JP', serif;
    font-weight:500;
    font-size:24px;
    line-height:1.6;
    margin:0 0 16px;
    color:#F4F1E8;
  }
  .brand-copy ul{
    list-style:none;
    margin:0;
    padding:0;
    max-width:320px;
  }
  .brand-copy li{
    display:flex;
    align-items:flex-start;
    gap:10px;
    font-size:13px;
    line-height:1.8;
    color:#C9C6BB;
    margin-bottom:14px;
  }
  .brand-copy li svg{
    width:16px; height:16px;
    flex-shrink:0;
    margin-top:2px;
    color:var(--accent);
  }
  .brand-foot{
    font-size:12px;
    color:#8B897F;
    position:relative;
    z-index:1;
  }

  /* Right form panel */
  .form-panel{
    flex:1;
    background:var(--bg);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px 40px;
  }
  .form-card{
    width:100%;
    max-width:420px;
  }
  .form-card h1{
    font-family:'Noto Serif JP', serif;
    font-size:22px;
    font-weight:500;
    color:var(--ink);
    margin:0 0 6px;
  }
  .form-card .lead{
    font-size:13px;
    color:var(--ink-soft);
    margin:0 0 30px;
  }

  .step-track{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:32px;
  }
  .step{
    flex:1;
    height:3px;
    border-radius:2px;
    background:var(--line);
  }
  .step.done{ background:var(--accent-deep); }

  .field{
    margin-bottom:18px;
  }
  .field-row{
    display:flex;
    gap:12px;
  }
  .field-row .field{ flex:1; }
  .field label{
    display:block;
    font-size:12px;
    color:var(--ink-soft);
    margin-bottom:8px;
    letter-spacing:0.02em;
  }
  .field input, .field select{
    width:100%;
    padding:13px 14px;
    font-size:14px;
    font-family:inherit;
    color:var(--ink);
    background:var(--card);
    border:1px solid var(--line);
    border-radius:6px;
    outline:none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    appearance:none;
  }
  .field input::placeholder{ color:#B9B6AA; }
  .field input:focus, .field select:focus{
    border-color: var(--accent-deep);
    box-shadow: 0 0 0 3px rgba(78,107,90,0.12);
  }
  .hint{
    font-size:11.5px;
    color:#9A978C;
    margin-top:6px;
  }

  .terms{
    display:flex;
    align-items:flex-start;
    gap:10px;
    font-size:12.5px;
    color:var(--ink-soft);
    line-height:1.6;
    margin:24px 0 26px;
  }
  .terms input{
    margin-top:3px;
    width:14px;
    height:14px;
    accent-color: var(--accent-deep);
    flex-shrink:0;
  }
  .link{
    color:var(--accent-deep);
    text-decoration:none;
    font-weight:500;
  }
  .link:hover{ text-decoration:underline; }

  .btn-primary{
    width:100%;
    padding:14px;
    background:var(--panel);
    color:#F4F1E8;
    border:none;
    border-radius:6px;
    font-size:14px;
    font-weight:700;
    letter-spacing:0.05em;
    cursor:pointer;
    transition: background 0.15s ease;
    margin-top: 20px;
  }
  .btn-primary:hover{ background:var(--panel-soft); }

  .signin{
    text-align:center;
    font-size:13px;
    color:var(--ink-soft);
    margin-top:20px;
  }

  @media (max-width: 900px){
    .brand-panel{ display:none; }
    .form-panel{ padding:24px; }
  }
</style>
</head>
<body>

  <section class="brand-panel">
    <div>
      <div class="brand-mark">みるまち</div>
      <div class="brand-sub">COMMUNITY PORTAL</div>
    </div>
    <div class="brand-copy">
      <h2>登録して、<br>地域とつながる。</h2>
      <ul>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 11l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
          町内会・自治会からのお知らせを受け取れます
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 11l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
          地域イベントの参加申込みができます
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 11l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
          アンケートで地域の声を届けられます
        </li>
      </ul>
    </div>
    <div class="brand-foot">© 2026 みるまち</div>
  </section>

  <section class="form-panel">
    <div class="form-card">
      <h1>新規登録</h1>
      <p class="lead">必要な情報を入力してアカウントを作成してください。</p>

      <div class="step-track">
        <div class="step done"></div>
        <div class="step"></div>
        <div class="step"></div>
      </div>

      <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div class="field-row">
          <div class="field">
            <label for="last-name">姓</label>
            <input
              id="last-name"
              name="last_name"
              type="text"
              placeholder="近藤"
              value="{{ old('last_name') }}"
            >
          </div>

          <div class="field">
            <label for="first-name">名</label>
            <input
              id="first-name"
              name="first_name"
              type="text"
              placeholder="太郎"
              value="{{ old('first_name') }}"
            >
          </div>
        </div>

        <div class="field">
          <label for="email">メールアドレス</label>
          <input
            id="email"
            name="email"
            type="email"
            placeholder="example@mirumachi.jp"
            value="{{ old('email') }}"
          >
        </div>

        <div class="field-row">
          <div class="field">
            <label for="region">お住まいの地域</label>
            <select id="region" name="region">
              <option value="">地域を選択してください</option>
              <option value="名古屋市中村区">名古屋市中村区</option>
              <option value="名古屋市西区">名古屋市西区</option>
              <option value="名古屋市中区">名古屋市中区</option>
              <option value="名古屋市東区">名古屋市東区</option>
            </select>
          </div>

          <div class="field">
            <label for="region2">丁目・詳細</label>
            <select id="region2" name="region2">
              <option value="">地域を選択してください</option>
              <option value="名駅2丁目">名駅2丁目</option>
              <option value="名駅3丁目">名駅3丁目</option>
              <option value="名駅4丁目">名駅4丁目</option>
            </select>
          </div>
        </div>

        <div class="hint">町内会・自治会の情報表示に使用します</div>

        <div class="field">
          <label for="password">パスワード</label>
          <input
            id="password"
            name="password"
            type="password"
            placeholder="8文字以上で入力"
          >
        </div>

        <div class="field">
          <label for="password-confirm">パスワード（確認）</label>
          <input
            id="password-confirm"
            name="password_confirmation"
            type="password"
            placeholder="もう一度入力してください"
          >
        </div>

        <button type="submit" class="btn-primary">
          アカウントを作成
        </button>
      </form>

      <p class="signin">
        すでにアカウントをお持ちの方は
        <a class="link" href="/login">ログイン</a>
      </p>
    </div>

  </section>

</body>
</html>