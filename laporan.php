<?php
include 'auth.php';
include 'db.php';
include 'navigation.php';

/* ==========================
   A. Sediakan senarai untuk filter
   ========================== */

// Senarai tahun (YEAR dari tarikh)
$yearList = array();
$sqlYear = "SELECT DISTINCT YEAR(tarikh) AS tahun 
            FROM penyelenggaraan 
            WHERE tarikh IS NOT NULL 
            ORDER BY tahun DESC";
$resYear = mysqli_query($conn, $sqlYear);
if ($resYear) {
    while ($row = mysqli_fetch_assoc($resYear)) {
        if ($row['tahun'] !== null) {
            $yearList[] = $row['tahun'];
        }
    }
}

// Senarai bil selenggara
$bilList = array();
$sqlBil = "SELECT DISTINCT bil_selenggara 
           FROM penyelenggaraan 
           WHERE bil_selenggara IS NOT NULL 
           ORDER BY bil_selenggara ASC";
$resBil = mysqli_query($conn, $sqlBil);
if ($resBil) {
    while ($row = mysqli_fetch_assoc($resBil)) {
        $bilList[] = $row['bil_selenggara'];
    }
}

// Senarai jabatan (nama_bahagian)
$jabatanList = array();
$sqlJab = "SELECT DISTINCT nama_bahagian 
           FROM penyelenggaraan 
           WHERE nama_bahagian IS NOT NULL AND nama_bahagian <> '' 
           ORDER BY nama_bahagian ASC";
$resJab = mysqli_query($conn, $sqlJab);
if ($resJab) {
    while ($row = mysqli_fetch_assoc($resJab)) {
        $jabatanList[] = $row['nama_bahagian'];
    }
}

/* ==========================
   B. Terima nilai filter dari form
   ========================== */

$tahunFilter       = isset($_GET['tahun']) ? trim($_GET['tahun']) : '';
$bilFilter         = isset($_GET['bil_selenggara']) ? trim($_GET['bil_selenggara']) : '';
$jabatanFilter     = isset($_GET['jabatan']) ? trim($_GET['jabatan']) : '';

$conditions = array();

// Filter tahun ikut YEAR(tarikh)
if ($tahunFilter !== '') {
    $conditions[] = "YEAR(tarikh) = " . intval($tahunFilter);
}

// Filter bil selenggara
if ($bilFilter !== '') {
    $conditions[] = "bil_selenggara = " . intval($bilFilter);
}

// Filter jabatan / bahagian
if ($jabatanFilter !== '') {
    $conditions[] = "nama_bahagian = '" . mysqli_real_escape_string($conn, $jabatanFilter) . "'";
}

/* ==========================
   C. Query data laporan
   ========================== */

$sqlLaporan = "SELECT 
    id,
    tarikh,
    nama_bahagian,
    nama_pengguna,
    juruteknik,
    pegawai,
    bil_selenggara,
    sesi_tahunan,
    status
  FROM penyelenggaraan";

if (!empty($conditions)) {
    $sqlLaporan .= " WHERE " . implode(" AND ", $conditions);
}

$sqlLaporan .= " ORDER BY tarikh DESC, nama_bahagian ASC";

$resLaporan = mysqli_query($conn, $sqlLaporan);

?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Laporan Penyelenggaraan ICT</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400..800&display=swap" rel="stylesheet">

<style>
/* ===== Tema latar gelap futuristik (konsisten dengan page lain) ===== */
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
  width: 100%; max-width: 1100px; margin: 0 auto;
}

/* Tajuk seksyen */
.futuristic-legend{
  margin: 10px 0 16px;
  font-weight: 800; letter-spacing:.03em;
  color: #d6e7ff;
}

/* BOX putih-silver untuk filter dan jadual */
.form-box,
.table-box{
  background: linear-gradient(135deg, #ffffff, #f2f2f2);
  border: 1px solid #000;        /* garis hitam */
  border-radius: 14px;
  padding: 18px 22px;
  box-shadow: 0 8px 20px rgba(0,0,0,.25);
  margin-bottom: 24px;
  color: #000;                   /* teks dalam box hitam */
}

/* Label & input */
.form-box label{
  color:#000;
  font-weight:700; display:block; margin-top:10px;
}
.form-box select,
.form-box input{
  width:100%; padding:8px 12px; margin-top:6px;
  border-radius:10px; border:1px solid #000;
  background:#fff; color:#000;
  outline: none;
}
.form-box select:focus,
.form-box input:focus{
  box-shadow: 0 0 0 3px rgba(0,0,0,.12);
}
.form-box ::placeholder{
  color:#000; opacity:.7;
}

/* Susunan filter dalam grid */
.filter-grid{
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px 24px;
}

.btn-submit{
  background: linear-gradient(180deg, #ffffff, #e6e6e6);
  color:#000; border:1px solid #000;
  font-family: "Oxanium", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  font-weight:800; letter-spacing:.06em; text-transform:uppercase;
  padding:10px 18px; border-radius:12px; margin-top:18px; cursor:pointer;
  transition: transform .12s ease, box-shadow .2s ease;
}
.btn-submit:hover{
  transform: translateY(-1px);
  box-shadow: 0 6px 14px rgba(0,0,0,.25);
}

/* Jadual laporan */
.table-wrapper{
  width:100%;
  overflow-x:auto;
}
.table-report{
  width:100%;
  border-collapse:collapse;
  font-size:13px;
}

/* kepala jadual */
.table-report thead{
  background:#e0e0e0;
}

/* semua teks dalam th & td warna hitam */
.table-report th,
.table-report td{
  padding:6px 10px;
  border-bottom:1px solid rgba(0,0,0,0.2);
  text-align:left;
  color:#000;              /* ← huruf hitam */
}

.table-report th{
  font-weight:700;
}

.table-report tbody tr:nth-child(even){
  background:#f9f9f9;
}
.table-report tbody tr:nth-child(odd){
  background:#ffffff;
}

/* style status */
.status-chip{
  display:inline-block;
  padding:2px 8px;
  border-radius:12px;
  font-size:11px;
  font-weight:600;
}
.status-Disahkan{
  background:#c8e6c9;
  color:#256029;
  border:1px solid #2e7d32;
}
.status-Belum{
  background:#ffe0b2;
  color:#e65100;
  border:1px solid #f57c00;
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
    <h2 class="futuristic-legend">LAPORAN PENYELENGGARAAN ICT</h2>

    <!-- ===================== BORANG FILTER ===================== -->
    <div class="form-box">
      <form method="get" action="laporan.php">
        <div class="filter-grid">
          <!-- Filter Tahun -->
          <div>
            <label>Tahun (berdasarkan tarikh)</label>
            <select name="tahun">
              <option value="">-- Semua Tahun --</option>
              <?php foreach($yearList as $thn): ?>
                <option value="<?php echo htmlspecialchars($thn, ENT_QUOTES, 'UTF-8'); ?>"
                  <?php echo ($tahunFilter == $thn ? 'selected' : ''); ?>>
                  <?php echo htmlspecialchars($thn, ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Filter Bil. Selenggara -->
          <div>
            <label>Bil. Selenggara</label>
            <select name="bil_selenggara">
              <option value="">-- Semua Bil --</option>
              <?php foreach($bilList as $bil): ?>
                <option value="<?php echo (int)$bil; ?>"
                  <?php echo ($bilFilter !== '' && (int)$bilFilter == (int)$bil ? 'selected' : ''); ?>>
                  <?php echo (int)$bil; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Filter Jabatan -->
          <div>
            <label>Jabatan / Bahagian</label>
            <select name="jabatan">
              <option value="">-- Semua Jabatan --</option>
              <?php foreach($jabatanList as $jab): ?>
                <option value="<?php echo htmlspecialchars($jab, ENT_QUOTES, 'UTF-8'); ?>"
                  <?php echo ($jabatanFilter == $jab ? 'selected' : ''); ?>>
                  <?php echo htmlspecialchars($jab, ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <button type="submit" class="btn-submit">Cari</button>
      </form>
    </div>

    <!-- ===================== JADUAL LAPORAN ===================== -->
    <div class="table-box">
      <h3 style="margin-top:0; margin-bottom:10px; font-weight:700;">Senarai Penyelenggaraan</h3>
      <div class="table-wrapper">
        <table class="table-report">
          <thead>
            <tr>
              <th>Bil</th>
              <th>Tarikh</th>
              <th>Tahun</th>
              <th>Bil. Selenggara</th>
              <th>Jabatan / Bahagian</th>
              <th>Nama Pengguna</th>
              <th>Juruteknik</th>
              <th>Pegawai</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php
          if ($resLaporan && mysqli_num_rows($resLaporan) > 0):
              $bilRow = 1;
              while ($row = mysqli_fetch_assoc($resLaporan)):
                  $tarikh = $row['tarikh'];
                  $tahun  = ($tarikh ? date('Y', strtotime($tarikh)) : '');
                  $status = $row['status'];
                  $statusClass = (strpos($status, 'Belum') !== false) ? 'status-Belum' : 'status-Disahkan';
          ?>
            <tr>
              <td><?php echo $bilRow++; ?></td>
              <td><?php echo htmlspecialchars($tarikh, ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($tahun, ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['bil_selenggara'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['nama_bahagian'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['nama_pengguna'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['juruteknik'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td><?php echo htmlspecialchars($row['pegawai'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td>
                <span class="status-chip <?php echo $statusClass; ?>">
                  <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </td>
            </tr>
          <?php
              endwhile;
          else:
          ?>
            <tr>
              <td colspan="9">Tiada rekod ditemui untuk penapis yang dipilih.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
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
