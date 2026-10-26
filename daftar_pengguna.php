<?php
include 'auth.php';
include 'db.php';
include 'navigation.php';

/* ==========================
   A. Data untuk <select> Jabatan
   ========================== */
$jabatanList = [];
$resJab = mysqli_query($conn, "SELECT DISTINCT jabatan FROM pengguna_jabatan ORDER BY jabatan ASC");
if ($resJab) {
  while ($r = mysqli_fetch_assoc($resJab)) {
    $jabatanList[] = $r['jabatan'];
  }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Daftar Pengguna Jabatan</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400..800&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ===== Tema latar gelap futuristik (tanpa partikel) ===== */
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

/* Grid dots neon — bergerak ke atas perlahan */
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

/* ====== BOX BORANG: gradient putih → silver, semua hitam ====== */
.form-box{
  background: linear-gradient(135deg, #ffffff, #f2f2f2);
  border: 1px solid #000;        /* garisan hitam */
  border-radius: 14px;
  padding: 20px 22px;
  box-shadow: 0 8px 20px rgba(0,0,0,.25);
  margin-bottom: 28px;
  color: #000;                   /* teks dalam box hitam */
}
.form-box label{
  color:#000;                    /* label hitam */
  font-weight:700; display:block; margin-top:10px;
}
.form-box select,
.form-box input{
  width:100%; padding:10px 12px; margin-top:6px;
  border-radius:10px; border:1px solid #000;   /* border hitam */
  background:#fff; color:#000;                 /* input putih, teks hitam */
  outline: none;
}
.form-box select:focus,
.form-box input:focus{
  box-shadow: 0 0 0 3px rgba(0,0,0,.12);       /* fokus gaya lembut, hitam */
}
.form-box ::placeholder{
  color:#000; opacity:.7;                      /* placeholder hitam */
}

/* Butang submit — font lawa (Oxanium) dan kekal tema putih-silver */
.btn-submit{
  background: linear-gradient(180deg, #ffffff, #e6e6e6);
  color:#000; border:1px solid #000;
  font-family: "Oxanium", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; /* ← font khas */
  font-weight:800; letter-spacing:.06em; text-transform:uppercase; /* bagi lebih “garang” */
  padding:12px 20px; border-radius:12px; margin-top:16px; cursor:pointer;
  transition: transform .12s ease, box-shadow .2s ease;
}
.btn-submit:hover{
  transform: translateY(-1px);
  box-shadow: 0 6px 14px rgba(0,0,0,.25);
}
.btn-submit:active{ transform: translateY(0) }

/* Sidebar toggle button — sentiasa biru */
.toggle-btn{
  position: fixed; top:15px; left:15px;
  z-index: 9999;
  display: inline-flex !important;
  align-items:center; gap:6px;

  /* <<< Biru asal >>> */
  background: #004aad;               /* kekal biru */
  color: #fff; 
  border: 1px solid #004aad;

  border-radius: 8px; padding: 8px 12px; cursor: pointer; font-size:18px;
  box-shadow: 0 6px 16px rgba(0,0,0,.25);
}
.toggle-btn:hover{
  background: #0066ff;               /* biru lebih cerah bila hover */
  border-color: #0066ff;
  box-shadow: 0 10px 24px rgba(0,0,0,.35);
}


/* SweetAlert (pilihan cerah) — biar kekal */
.swal-bright-popup {
  border-radius: 18px !important;
  background: linear-gradient(135deg, #ffffff, #e3f2fd, #f1f8e9);
  box-shadow: 0 0 25px rgba(150, 200, 255, 0.3), inset 0 0 10px rgba(255,255,255,0.4);
  padding: 25px; backdrop-filter: blur(6px);
}
.swal-bright-title {
  font-family: 'Poppins', sans-serif; color: #007BFF; font-weight: 600; font-size: 1.4rem;
  text-transform: uppercase; text-shadow: 0 0 10px rgba(100, 170, 255, 0.4);
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

<?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'/>
<script>
Swal.fire({
  title: '✅ BERJAYA TAMBAH PENGGUNA',
  text: 'Rekod telah dimasukkan dengan jayanya!',
  icon: 'success',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
background: '#ffffff',
  customClass: { popup: 'swal-bright-popup animate__animated animate__zoomIn', title: 'swal-bright-title' },
  showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
  hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
}).then(() => {
  if (window.location.search.includes('success=1')) {
    const cleanURL = window.location.origin + window.location.pathname;
    window.location.replace(cleanURL);
  }
});
</script>
<?php endif; ?>

<button class="toggle-btn" onclick="document.querySelector('.sidebar') && document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<div class="content">
  <div class="content-dashboard">
    <h2 class="futuristic-legend">BORANG TAMBAH PENGGUNA</h2>

    <!-- Borang tambah pengguna :: BOX PUTIH-SILVER, TEKS & GARIS HITAM -->
    <div class="form-box">
      <form method="post" action="tambah_pengguna.php">
        <label>Nama Jabatan</label>
        <select name="jabatan" required>
          <option value="">-- Sila Pilih Jabatan --</option>
          <?php if(!empty($jabatanList)): ?>
            <?php foreach($jabatanList as $jab): ?>
              <option value="<?= htmlspecialchars($jab, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($jab, ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="">Tiada Jabatan</option>
          <?php endif; ?>
        </select>

        <label>Nama Pengguna</label>
        <input type="text" name="nama_pengguna" required
               placeholder="Contoh: AZLAN BIN ALI"
               style="text-transform: uppercase;"
               oninput="this.value = this.value.toUpperCase();">

        <button type="submit" class="btn-submit">Tambah Pengguna</button>
      </form>
    </div>

    <!-- (Dibuang) Senarai pengguna mengikut jabatan -->

  </div>
</div>

<script>
// Sidebar toggle margin-left sync
const sidebar = document.querySelector('.sidebar');
const content = document.querySelector('.content');
function syncMargin(){
  if(!sidebar || !content) return;
  content.style.marginLeft = sidebar.classList.contains('collapsed') ? '80px' : '250px';
}
syncMargin();
(new MutationObserver(syncMargin)).observe(document.body, { attributes:true, subtree:true });
</script>

</body>
</html>
