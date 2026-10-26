<?php
include 'auth.php';
include 'db.php';
include 'navigation.php';

// Proses simpan data bila submit borang
$errMsg   = '';
$success  = false; // flag untuk tahu sama ada simpanan berjaya

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Guna isset() supaya serasi dengan PHP versi lama
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $level    = isset($_POST['level'])    ? $_POST['level']          : '';

    // Pastikan level hanya adminA atau adminB
    $allowedLevels = array('adminA', 'adminB');

    if ($username == '' || $password == '' || !in_array($level, $allowedLevels)) {
        $errMsg = 'Sila lengkapkan semua maklumat dan pilih peranan yang sah.';
    } else {
        // Semak username tak duplikasi
        $stmtCheck = $conn->prepare("SELECT id FROM pengguna WHERE username = ?");
        if ($stmtCheck) {
            $stmtCheck->bind_param("s", $username);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $errMsg = 'Nama pengguna sudah wujud. Sila guna ID lain.';
            } else {
                // Simpan ke table pengguna – password ikut contoh asal (plain text)
                $stmt = $conn->prepare("INSERT INTO pengguna (username, password, level) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sss", $username, $password, $level);
                    if ($stmt->execute()) {
                        // ✅ Berjaya – TIDAK guna header(), cuma tetapkan flag
                        $success = true;
                        // optional: kosongkan field
                        $username = '';
                        $password = '';
                        $level    = '';
                    } else {
                        $errMsg = 'Ralat semasa menyimpan data. Sila cuba lagi.';
                    }
                    $stmt->close();
                } else {
                    $errMsg = 'Ralat pada penyataan SQL (INSERT).';
                }
            }
            $stmtCheck->close();
        } else {
            $errMsg = 'Ralat pada penyataan SQL (SELECT).';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Tambah Pegawai Teknikal</title>
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

/* mesej ralat ringkas */
.error-msg{
  margin-top:10px;
  color:#c62828;
  font-weight:600;
  font-size:.9rem;
}

/* Butang submit — font Oxanium & tema putih-silver */
.btn-submit{
  background: linear-gradient(180deg, #ffffff, #e6e6e6);
  color:#000; border:1px solid #000;
  font-family: "Oxanium", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  font-weight:800; letter-spacing:.06em; text-transform:uppercase;
  padding:12px 20px; border-radius:12px; margin-top:16px; cursor:pointer;
  transition: transform .12s ease, box-shadow .2s ease;
}
.btn-submit:hover{
  transform: translateY(-1px);
  box-shadow: 0 6px 14px rgba(0,0,0,.25);
}
.btn-submit:active{ transform: translateY(0) }

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

/* SweetAlert cerah */
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

<?php if($success): ?>
<script>
Swal.fire({
  title: '✅ BERJAYA TAMBAH PEGAWAI',
  text: 'Rekod pegawai telah dimasukkan dengan jayanya!',
  icon: 'success',
  showConfirmButton: true,
  confirmButtonText: 'Tutup',
  background: '#ffffff',
  customClass: { popup: 'swal-bright-popup', title: 'swal-bright-title' }
});
</script>
<?php endif; ?>

<button class="toggle-btn" onclick="document.querySelector('.sidebar') && document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<div class="content">
  <div class="content-dashboard">
    <h2 class="futuristic-legend">BORANG TAMBAH PEGAWAI TEKNIKAL / PEMANTAU</h2>

    <div class="form-box">
      <form method="post" action="add_juruteknik.php">
        <label>ID / Username</label>
        <input type="text" name="username" required
               placeholder="Contoh: 10330MDTM"
               style="text-transform: uppercase;"
               oninput="this.value = this.value.toUpperCase();">

        <label>Kata Laluan</label>
        <input type="text" name="password" required
               placeholder="Masukkan kata laluan">

        <label>Peranan (Role)</label>
        <select name="level" required>
          <option value="">-- Sila Pilih Peranan --</option>
          <!-- Papar nama mesra, tapi value simpan adminA / adminB -->
          <option value="adminA">Pegawai Teknikal</option>
          <option value="adminB">Pegawai Pemantau</option>
        </select>

        <?php if($errMsg != ''): ?>
          <div class="error-msg"><?php echo htmlspecialchars($errMsg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <button type="submit" class="btn-submit">Tambah Pegawai</button>
      </form>
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
