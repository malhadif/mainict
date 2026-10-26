<?php
// === VERSI MESRA PHP LAMA (tiada '??' atau '?:') ===

// Tetapan cookie asas (tanpa SameSite untuk keserasian PHP lama)
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
session_set_cookie_params(0, '/');
session_start();
require_once 'db.php';

// CSRF token (fallback jika tiada random_bytes)
if (empty($_SESSION['csrf_token'])) {
    $bytes = '';
    if (function_exists('random_bytes')) {
        $bytes = random_bytes(32);
    } else {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(32);
        } else {
            // fallback terakhir (kurang selamat, sekadar elak ralat pada PHP sangat lama)
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $tmp = '';
            $i = 0;
            while ($i < 32) {
                $tmp .= $pool[rand(0, strlen($pool) - 1)];
                $i++;
            }
            $bytes = $tmp;
        }
    }
    $_SESSION['csrf_token'] = bin2hex($bytes);
}

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dapatkan CSRF tanpa '??' atau ternary
    $post_csrf = '';
    if (isset($_POST['csrf_token'])) {
        $post_csrf = $_POST['csrf_token'];
    }

    if (empty($post_csrf) || $post_csrf !== $_SESSION['csrf_token']) {
        $login_error = 'Sesi tidak sah. Sila cuba lagi.';
    } else {
        // Ambil input tanpa ternary
        $username = '';
        if (isset($_POST['username'])) {
            $username = trim($_POST['username']);
        }
        $password = '';
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        }

        // Prepared statement serasi lama (tanpa mysqli_stmt_get_result)
        $stmt = mysqli_prepare($conn, 'SELECT id, username, password, level FROM pengguna WHERE username = ? LIMIT 1');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $uid, $uname, $hash, $level);
            $found = mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            $ok = false;
            if ($found && !empty($uname)) {
                // Jika panjang hash >= 60, anggap sudah di-hash dan guna password_verify.
                // Jika tidak (data lama / belum di-hash), guna perbandingan biasa.
                if (function_exists('password_verify') && strlen($hash) >= 60) {
                    if (password_verify($password, $hash)) {
                        $ok = true;
                    }
                } else {
                    // fallback: password dalam DB masih plaintext
                    if ($password === $hash) {
                        $ok = true;
                    }
                }
            }

            if ($ok) {
                session_regenerate_id(true);
                $_SESSION['username']   = trim($uname); // pastikan tiada space tersembunyi
                $_SESSION['level']      = $level;
                $_SESSION['session_id'] = session_id();

                $stmt2 = mysqli_prepare($conn, 'UPDATE pengguna SET session_id = ? WHERE id = ?');
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, 'si', $_SESSION['session_id'], $uid);
                    mysqli_stmt_execute($stmt2);
                    mysqli_stmt_close($stmt2);
                }

                header('Location: index.php');
                exit;
            } else {
                $login_error = 'Username atau kata laluan tidak sah.';
            }
        } else {
            $login_error = 'Ralat pelayan. Sila cuba lagi.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SISTEM PENYELENGGARAAN ICT</title>
  <link rel="icon" type="image/png" href="ictlogo.png" />

  <!-- Fonts & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />

  <style>
  /* === Kekalkan input gelap walau diisi/focus/autofill === */
:root{ --input-bg: rgba(255,255,255,.06); }

/* Sentiasa gelap + teks putih */
.form-control{
  background: var(--input-bg) !important;
  color:#fff !important;
  caret-color:#fff;
  -webkit-text-fill-color:#fff; /* bantu Safari/Chrome */
}

/* Fokus pun kekal gelap */
.form-control:focus{
  background: var(--input-bg) !important;
  color:#fff !important;
  box-shadow:none; /* jika nak buang glow luar browser */
}

/* Bila ada nilai (bukan placeholder), kekal gelap */
.form-control:not(:placeholder-shown){
  background: var(--input-bg) !important;
  color:#fff !important;
}

/* Placeholder putih */
.form-control::placeholder{ color:#fff; opacity:.9; }
.form-control::-webkit-input-placeholder{ color:#fff; opacity:.9; }
.form-control:-ms-input-placeholder{ color:#fff; opacity:.9; }
.form-control::-ms-input-placeholder{ color:#fff; opacity:.9; }

/* --- Chrome/Edge (autofill kuning) — paksa gelap --- */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus{
  -webkit-text-fill-color:#fff;
  caret-color:#fff;
  box-shadow: 0 0 0 1000px var(--input-bg) inset !important; /* isi dgn bg gelap */
  transition: background-color 9999s ease-out 0s; /* elak flash putih */
}

/* --- Firefox (autofill) --- */
input:-moz-autofill{
  box-shadow: 0 0 0 1000px var(--input-bg) inset;
  -moz-text-fill-color:#fff;
  caret-color:#fff;
}

  
/* Teks input putih */
#username.form-control,
#password.form-control {
  color: #fff;
}

/* Placeholder putih (semua pelayar utama) */
#username::placeholder,
#password::placeholder { color:#fff; opacity:0.9; }
#username::-webkit-input-placeholder,
#password::-webkit-input-placeholder { color:#fff; opacity:0.9; }  /* Chrome/Safari/Edge baru */
#username:-ms-input-placeholder,
#password:-ms-input-placeholder { color:#fff; opacity:0.9; }       /* IE10–11 */
#username::-ms-input-placeholder,
#password::-ms-input-placeholder { color:#fff; opacity:0.9; }      /* Edge lama */

/* Jika Chrome autofill tukar warna sendiri, paksa ikut tema gelap */
input:-webkit-autofill,
input:-webkit-autofill:focus {
  -webkit-text-fill-color: #fff;
  box-shadow: 0 0 0 30px rgba(255,255,255,0.06) inset !important;
  caret-color: #fff;
}


    :root{
      --bg-start:#030014; /* deep space */
      --bg-end:#001a37;   /* deep blue */
      --neon:#00d9ff;
      --neon-2:#7c4dff;
      --card:#0b1020cc; /* translucent */
      --text:#e6eeff;
      --muted:#9ab0d0;
      --accent:#2ee6a6;
    }

    *{ box-sizing:border-box }
    html,body{ height:100% }
    body{
      margin:0; display:flex; align-items:center; justify-content:center; min-height:100vh;
      font-family:'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color:var(--text);
      background:
        radial-gradient(1200px 800px at 10% 10%, #0a1a33 0%, transparent 60%),
        radial-gradient(900px 700px at 90% 20%, #0b002b 0%, transparent 70%),
        linear-gradient(160deg, var(--bg-start), var(--bg-end));
      overflow:hidden;
    }

    /* Animated stars (BUANG ANIMATION - jadi statik untuk ringan) */
    .stars, .stars:before, .stars:after{
      content:""; position:absolute; inset:0; background-repeat:repeat; pointer-events:none;
      background-image:
        radial-gradient(2px 2px at 20px 30px, rgba(255,255,255,.7) 50%, transparent 51%),
        radial-gradient(1px 1px at 100px 80px, rgba(255,255,255,.5) 50%, transparent 51%),
        radial-gradient(1px 1px at 200px 120px, rgba(255,255,255,.4) 50%, transparent 51%);
      /* animation: drift 120s linear infinite;  <-- dibuang */
      opacity:.25; /* sedikit rendah supaya ringan */
      z-index:0;
    }
    .stars:before{ /* animation-duration: 180s; */ opacity:.18 }
    .stars:after{ /* animation-duration: 240s; */ opacity:.14 }
    @keyframes drift{ from{ background-position:0 0 } to{ background-position:-2000px -2000px } }

    /* Neon orb blobs (BUANG ANIMATION FLOAT - jadi statik) */
    .orb{
      position:absolute; width:60vmin; height:60vmin; border-radius:50%; filter: blur(70px);
      background: radial-gradient(circle at 30% 30%, var(--neon), transparent 60%);
      mix-blend-mode: screen; opacity:.28; /* turunkan opacity utk ringan */
      /* animation: float 14s ease-in-out infinite; <-- dibuang */
      z-index:0;
    }
    .orb.two{
      background: radial-gradient(circle at 70% 70%, var(--neon-2), transparent 60%);
      /* animation-duration: 18s; <-- dibuang */
      opacity:.24;
    }
    @keyframes float{ 0%,100%{ transform: translate(0,0) } 50%{ transform: translate(0,-40px) } }

    /* Grid dots futuristik (DOT DOT STATIK - TIADA ANIMATION) */
    .grid-dots{
      position:fixed; inset:0; pointer-events:none; z-index:0;
      background-image:
        radial-gradient(circle at 12px 12px, rgba(0,217,255,.35) 1.2px, transparent 1.3px),
        radial-gradient(circle at 6px 6px, rgba(124,77,255,.25) 1px, transparent 1.1px);
      background-size: 24px 24px, 24px 24px;
      opacity:.22; mix-blend-mode: screen;
    }

    /* Futuristic card (BUANG ANIMATION RISE - terus statik untuk smooth) */
    .login-card{
      position:relative; width:min(92vw, 440px); padding:32px 28px; border-radius:24px; backdrop-filter: blur(14px);
      background: linear-gradient(180deg, rgba(16,24,48,.65), rgba(8,12,28,.65));
      border:1px solid rgba(120,160,255,.2);
      box-shadow: 0 10px 40px rgba(0,0,0,.55), inset 0 0 0 1px rgba(255,255,255,.04);
      /* transform: translateY(18px); opacity:0; animation: rise .8s ease .2s forwards;  <-- dibuang */
      z-index:1;
    }
    @keyframes rise{ to{ transform: translateY(0); opacity:1 } }

    /* Glow frame (TIADA ANIMATION PUSING - kekal statik) */
    .glow{
      position:absolute; inset:-2px; border-radius:24px; padding:2px; background:
        conic-gradient(from 180deg at 50% 50%,
        transparent 0 10%, rgba(0,217,255,.35) 12%, transparent 35%,
        rgba(124,77,255,.35) 50%, transparent 75%, rgba(46,230,166,.35) 88%, transparent 100%);
      -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite: xor; mask-composite: exclude;
      filter: blur(8px); /* blur kurangkan sikit untuk ringan */
      opacity:.6;
    }
    @keyframes spin{ to{ transform: rotate(360deg) } }

    .brand{ display:flex; align-items:center; gap:12px; margin-bottom:14px }
    .brand img{ width:52px; height:52px; object-fit:contain; filter: drop-shadow(0 0 10px rgba(0,217,255,.35)); }
    .brand h1{ font-size:1.05rem; font-weight:800; letter-spacing:.06em; margin:0; text-transform:uppercase }

    .subtitle{ color:var(--muted); font-size:.9rem; margin-bottom:18px }

    /* Ticker (KEKAL STYLE, TAPI KOTAK TICKER AKAN DIBUANG DI HTML) */
    .ticker{ position:relative; overflow:hidden; border:1px solid rgba(124,77,255,.25); border-radius:12px; background: rgba(12,18,36,.6); }
    .ticker-inner{
      white-space:normal;
      padding:8px 12px;
      color:#cfe6ff; font-weight:600; letter-spacing:.03em;
      text-align:center;
    }

    .form-control, .form-control:focus{
      background: rgba(255,255,255,.06); border:1px solid rgba(160,200,255,.22); color:var(--text);
      box-shadow:none; border-radius:12px;
      padding:12px 52px 12px 44px; /* ruang kanan utk butang mata */
      outline:0;
    }
    .input-wrap:focus-within .form-control{
      border-color: rgba(0,217,255,.55);
      box-shadow: 0 0 0 3px rgba(0,217,255,.15);
    }
    label{ font-size:.85rem; color:var(--muted); margin:10px 2px 6px }

    .input-wrap{ position:relative }
    .input-wrap .bi{ position:absolute; left:12px; top:50%; transform:translateY(-50%); opacity:.7 }

    /* Butang mata DI DALAM input */
    .toggle-pass{
      position:absolute; right:8px; top:50%; transform: translateY(-50%);
      width:36px; height:36px; display:flex; align-items:center; justify-content:center;
      background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.04));
      border:1px solid rgba(160,200,255,.25);
      border-radius:10px; color:var(--text); cursor:pointer;
      transition:transform .12s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .toggle-pass:hover{
      transform: translateY(-50%) scale(1.03);
      border-color: rgba(0,217,255,.55);
      box-shadow: 0 0 0 3px rgba(0,217,255,.12), 0 0 20px rgba(0,217,255,.25);
    }
    .toggle-pass:active{ transform: translateY(-50%) scale(.98) }
    .toggle-pass i{ opacity:.9; font-size:1.05rem }

    .btn-neon{
      --glow: drop-shadow(0 0 10px var(--neon)) drop-shadow(0 0 25px var(--neon));
      width:100%; border:1px solid rgba(0,217,255,.35); color:#00121e; font-weight:800; letter-spacing:.06em;
      background: linear-gradient(180deg, #7df2ff, #2dd2ff);
      border-radius:12px; padding:12px 16px; text-transform:uppercase;
      transition: transform .12s ease, filter .2s ease; filter: var(--glow);
    }
    .btn-neon:hover{ transform: translateY(-1px) scale(1.01) }
    .btn-neon:active{ transform: translateY(0) scale(.997) }

    .footer{ color:var(--muted); font-size:.78rem; text-align:center; margin-top:14px }
    .toast-container{ position: fixed; top: 14px; right: 14px; z-index: 1080 }
  </style>
</head>
<body>
  <!-- Background layers -->
  <div class="stars"></div>
  <div class="grid-dots"></div>
  <div class="orb" style="top:10%; left:-10%"></div>
  <div class="orb two" style="bottom:-10%; right:-10%"></div>

  <main class="login-card">
    <div class="glow"></div>

    <div class="brand">
      <a href="https://www.mdtm.gov.my/" class="d-inline-flex" target="_blank" rel="noopener">
        <img src="mdtm.png" alt="MDTM" />
      </a>
      <h1>Sistem Penyelenggaraan Berkala ICT</h1>
    </div>
    <div class="subtitle">MDTM • Log Masuk</div>

    <!-- TICKER DIBUANG (awak nak buang ayat panjang tu) -->

    <?php if (!empty($login_error)): ?>
      <div class="alert alert-danger py-2 px-3" role="alert">
        <i class="bi bi-shield-exclamation me-1"></i> <?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form method="post" autocomplete="off" novalidate>
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

      <label for="username" class="form-label">Username</label>
      <div class="input-wrap mb-2">
        <i class="bi bi-person"></i>
        <input type="text" id="username" name="username" class="form-control" placeholder="cth: 12345MDTM" required>
      </div>

      <label for="password" class="form-label">Kata Laluan</label>
      <div class="input-wrap mb-3">
        <i class="bi bi-lock"></i>
        <input type="password" id="password" name="password" class="form-control" placeholder="•••" required>
        <button type="button" class="toggle-pass" aria-label="Tunjuk/sembunyi kata laluan" title="Tunjukkan kata laluan"><i class="bi bi-eye"></i></button>
      </div>

      <button type="submit" class="btn btn-neon"><i class="bi bi-box-arrow-in-right me-1"></i> Log Masuk</button>
    </form>

    <div class="footer">
      <?php include 'footer.php'; ?>
    </div>
  </main>

  <div class="toast-container"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility (tiada syntax moden)
    var toggleBtn = document.querySelector('.toggle-pass');
    var passInput = document.getElementById('password');
    var shown = false;

    function updateEye(){
      toggleBtn.innerHTML = shown ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
      toggleBtn.setAttribute('aria-pressed', shown ? 'true' : 'false');
      toggleBtn.setAttribute('title', shown ? 'Sembunyikan kata laluan' : 'Tunjukkan kata laluan');
    }
    updateEye();

    toggleBtn.addEventListener('click', function(){
      shown = !shown;
      passInput.type = shown ? 'text' : 'password';
      updateEye();
    });

    // Validasi klien ringkas
    var theForm = document.querySelector('form');
    theForm.addEventListener('submit', function(e){
      var u = document.getElementById('username').value.replace(/^\s+|\s+$/g, '');
      var p = passInput.value.replace(/^\s+|\s+$/g, '');
      if(!u || !p){
        e.preventDefault();
        showToast('Sila lengkapkan semua medan.');
      }
    });

    function showToast(msg){
      var wrap = document.querySelector('.toast-container');
      var el = document.createElement('div');
      el.className = 'toast align-items-center text-bg-danger border-0 show';
      el.setAttribute('role','alert');
      el.innerHTML =
        '<div class="d-flex">' +
          '<div class="toast-body"><i class="bi bi-info-circle me-2"></i>' + msg + '</div>' +
          '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
        '</div>';
      wrap.appendChild(el);
      setTimeout(function(){ if(el.parentNode){ el.parentNode.removeChild(el); } }, 3000);
    }
  </script>
</body>
</html>
