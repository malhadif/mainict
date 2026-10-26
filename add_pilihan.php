<?php
include 'auth.php';
include 'db.php';
include 'navigation.php';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Pilihan Tambah Pengguna / Pegawai</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400..800&display=swap" rel="stylesheet">

<style>
/* ===== Tema latar gelap futuristik (konsisten) ===== */
:root{
  --bg-start:#030014;
  --bg-end:#001a37;
  --text:#e6eeff;
  --muted:#9ab0d0;
}

*{ box-sizing:border-box }
html,body{ height:100% }
body{
  background:
    radial-gradient(1200px 800px at 10% 10%, #0a1a33 0%, transparent 60%),
    radial-gradient(900px 700px at 90% 20%, #0b002b 0%, transparent 70%),
    linear-gradient(160deg, var(--bg-start), var(--bg-end));
  margin:0; padding:0; min-height:100vh; overflow-x:hidden; color:var(--text);
  font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

/* Grid dots neon */
.grid-dots{
  position:fixed; inset:0; z-index:0; pointer-events:none;
  background-image:
    radial-gradient(circle at 12px 12px, rgba(0,217,255,.35) 1.2px, transparent 1.3px),
    radial-gradient(circle at 6px 6px, rgba(124,77,255,.25) 1px, transparent 1.1px);
  background-size: 24px 24px, 24px 24px;
  animation: dots-move 80s linear infinite;
  opacity:.28; mix-blend-mode: screen;
}
@keyframes dots-move{
  from{ background-position: 0 180% , 0 120% }
  to  { background-position: 0 -60% , 0 -20% }
}

/* Layout content / sidebar */
.content{
  position: relative; z-index: 1;
  padding: 25px 40px;
  transition: margin-left 0.3s ease;
  margin-left: 250px; /* default lebar sidebar */
}
.sidebar.collapsed ~ .content{ margin-left: 80px; }

.content-dashboard{
  width: 100%; max-width: 900px; margin: 0 auto;
}

/* Tajuk seksyen */
.futuristic-legend{
  margin: 10px 0 16px;
  font-weight: 800; letter-spacing:.03em;
  color: #d6e7ff;
}

/* ===== Box pilihan menu ===== */
.menu-grid{
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
  justify-content: center;
  margin-top: 20px;
}

.menu-card{
  background: linear-gradient(135deg, #ffffff, #f2f2f2);
  border: 1px solid #000;
  border-radius: 16px;
  width: 220px;                 /* sederhana besar */
  min-height: 220px;
  box-shadow: 0 8px 20px rgba(0,0,0,.25);
  text-align: center;
  padding: 18px 16px;
  color: #000;
  text-decoration: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Icon bulat */
.menu-card-icon{
  width: 90px;
  height: 90px;
  border-radius: 50%;
  border: 2px solid #000;
  display: flex;
  align-items: center;
  justify-content: center;
  background:#fff;
  margin-bottom: 10px;
  overflow:hidden;
}
.menu-card-icon img{
  max-width: 70%;
  max-height: 70%;
}

/* Text tajuk card */
.menu-card-title{
  font-family: "Oxanium", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  font-weight: 800;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: .06em;
}

/* Text kecil bawah tajuk */
.menu-card-sub{
  font-size: .8rem;
  margin-top: 4px;
  color:#333;
}

/* Hover sederhana, TANPA zoom gila-gila */
.menu-card:hover{
  box-shadow: 0 10px 26px rgba(0,0,0,.35);
}

/* Sidebar toggle button — biru */
.toggle-btn{
  position: fixed; top:15px; left:15px;
  z-index: 9999;
  display: inline-flex !important;
  align-items:center; gap:6px;

  background: #004aad;
  color: #fff; 
  border: 1px solid #004aad;

  border-radius: 8px; padding: 8px 12px; cursor: pointer; font-size:18px;
  box-shadow: 0 6px 16px rgba(0,0,0,.25);
}
.toggle-btn:hover{
  background: #0066ff;
  border-color: #0066ff;
  box-shadow: 0 10px 24px rgba(0,0,0,.35);
}

/* Hormati pengguna yang elak animasi */
@media (prefers-reduced-motion: reduce){
  .grid-dots{ animation: none; }
}
</style>
</head>
<body>

<!-- Grid dots (latar) -->
<div class="grid-dots"></div>

<button class="toggle-btn" onclick="document.querySelector('.sidebar') && document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<div class="content">
  <div class="content-dashboard">
    <h2 class="futuristic-legend">PILIHAN TAMBAH PENGGUNA / PEGAWAI</h2>

    <div class="menu-grid">

      <!-- Kad 1: Tambah Pengguna (daftar_pengguna.php) -->
      <a href="daftar_pengguna.php" class="menu-card">
        <div class="menu-card-icon">
          <!-- icon pengguna -->
          <img src="user.png" alt="Tambah Pengguna">
        </div>
        <div class="menu-card-title">Tambah Pengguna</div>
        <div class="menu-card-sub">Daftar pengguna jabatan</div>
      </a>

      <!-- Kad 2: Tambah Pegawai (add_juruteknik.php) -->
      <a href="add_juruteknik.php" class="menu-card">
        <div class="menu-card-icon">
          <!-- icon pegawai teknikal/pemantau -->
          <img src="peg.png" alt="Tambah Pegawai">
        </div>
        <div class="menu-card-title">Tambah Pegawai</div>
        <div class="menu-card-sub">Pegawai teknikal & pemantau</div>
      </a>

    </div>

  </div>
</div>

<script>
// Sidebar toggle margin-left sync
var sidebar = document.querySelector('.sidebar');
var content = document.querySelector('.content');
function syncMargin(){
  if(!sidebar || !content) return;
  content.style.marginLeft = sidebar.classList.contains('collapsed') ? '80px' : '250px';
}
syncMargin();
(new MutationObserver(syncMargin)).observe(document.body, { attributes:true, subtree:true });
</script>

</body>
</html>
