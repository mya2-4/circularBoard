<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>みるまち - ログイン</title>
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
    --danger: #A9673B;
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
    width:44%;
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
    font-size:26px;
    line-height:1.6;
    margin:0 0 16px;
    color:#F4F1E8;
  }
  .brand-copy p{
    font-size:13px;
    line-height:1.9;
    color:#C9C6BB;
    max-width:340px;
    margin:0;
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
    padding:40px;
  }
  .form-card{
    width:100%;
    max-width:380px;
  }

  .error-message {
    color: red;
    font-size: 15px;
    margin-bottom: 15px;
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
    margin:0 0 36px;
  }
  .field{
    margin-bottom:20px;
  }
  .field label{
    display:block;
    font-size:12px;
    color:var(--ink-soft);
    margin-bottom:8px;
    letter-spacing:0.02em;
  }
  .field input{
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
  }
  .field input::placeholder{ color:#B9B6AA; }
  .field input:focus{
    border-color: var(--accent-deep);
    box-shadow: 0 0 0 3px rgba(78,107,90,0.12);
  }
  .row-between{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin:-6px 0 28px;
    font-size:12.5px;
  }
  .remember{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--ink-soft);
  }
  .remember input{
    width:14px;
    height:14px;
    accent-color: var(--accent-deep);
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
  }
  .btn-primary:hover{ background:var(--panel-soft); }

  .divider{
    display:flex;
    align-items:center;
    gap:12px;
    margin:28px 0;
    color:#B9B6AA;
    font-size:11px;
    letter-spacing:0.08em;
  }
  .divider::before, .divider::after{
    content:"";
    flex:1;
    height:1px;
    background:var(--line);
  }

  .signup{
    text-align:center;
    font-size:13px;
    color:var(--ink-soft);
  }

  @media (max-width: 860px){
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
      <h2>暮らしの情報を、<br>もっと身近に。</h2>
      <p>町内会・自治会からのお知らせ、地域イベント、アンケートまで。みるまちで、あなたの街の"今"をお届けします。</p>
    </div>
    <div class="brand-foot">© 2026 みるまち</div>
  </section>

  <section class="form-panel">
    <div class="form-card">
      <h1>ログイン</h1>
      <p class="lead">アカウント情報を入力してください。</p>

        <form action="{{ route('login.store') }}" method="POST">
          @csrf

          @if ($errors->any())
            <div class="error-message">
              {{ $errors->first() }}
            </div>
          @endif

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

          <div class="field">
            <label for="password">パスワード</label>
            <input
              id="password"
              name="password"
              type="password"
              placeholder="パスワードを入力"
            >
          </div>

          <div class="row-between">
            <label class="remember">
              <input type="checkbox" name="remember">
              ログイン状態を保存
            </label>

            <a class="link" href="#">
              パスワードをお忘れの方
            </a>
          </div>

          <button type="submit" class="btn-primary">
            ログイン
          </button>
        </form>


  </section>

</body>
</html>