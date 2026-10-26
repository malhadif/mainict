<?php 
// ================= Dashboard Penyelenggaraan ICT (Tema Futuristik Tanpa Partikel) =================
include 'auth.php'; 
include 'db.php'; 
include 'navigation.php'; 


// Ambil tahun dari URL (GET) — elak notis pada PHP lama
$tahun = "";
if (isset($_GET['tahun'])) {
    $tahun = intval($_GET['tahun']);
    if ($tahun <= 0) { $tahun = ""; }
}

// Sediakan syarat ikut tahun
$whereTahun = ($tahun !== "") ? "AND YEAR(tarikh) = '".mysqli_real_escape_string($conn, $tahun)."'" : "";

// Query statistik ikut tahun (atau semua jika kosong)
$res1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM penyelenggaraan WHERE status='Disahkan' $whereTahun");
$row1 = mysqli_fetch_assoc($res1);
$totalDisahkan = isset($row1['total']) ? (int)$row1['total'] : 0;

$res2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM penyelenggaraan WHERE status='Belum Disahkan' $whereTahun");
$row2 = mysqli_fetch_assoc($res2);
$totalSelenggara = isset($row2['total']) ? (int)$row2['total'] : 0;

$res3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM penyelenggaraan WHERE 1=1 $whereTahun");
$row3 = mysqli_fetch_assoc($res3);
$totalSemua = isset($row3['total']) ? (int)$row3['total'] : 0;

// Kira peratus (elak bahagi 0)
$peratusDisahkan = ($totalSemua > 0) ? round(($totalDisahkan / $totalSemua) * 100, 1) : 0;
$peratusBelum    = ($totalSemua > 0) ? round(($totalSelenggara / $totalSemua) * 100, 1) : 0;

// === Sediakan senarai bahagian lebih awal (supaya tak undefined)
$bahagianQuery = mysqli_query($conn, "
    SELECT DISTINCT nama_bahagian 
    FROM penyelenggaraan 
    WHERE 1=1 $whereTahun
    ORDER BY nama_bahagian ASC
");
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penyelenggara - ICT MDTM</title>
    <link rel="stylesheet" href="style.css">

    <style>
/* Jarak “SENARAI BORANG” daripada butang Isi Borang */
.gap-isi-senarai { margin-top: 28px; }  /* boleh ubah 24/32/40px */

/* Gap bawah keseluruhan seksyen Senarai Borang */
.senarai-borang { margin-bottom: 38px; } /* ubah ikut selera: 32–64px */

/* ===== Tema latar gelap futuristik (tanpa partikel) ===== */
:root{
  --bg-start:#030014; /* deep space */
  --bg-end:#001a37;   /* deep blue */
  --neon:#00d9ff;
  --neon-2:#7c4dff;
  --panel:#0b1020cc;  /* translucent panel */
  --text:#e6eeff;
  --muted:#9ab0d0;
  --brand:#004aad;
}
/* Jarak antara tajuk "SENARAI BORANG" dan kotak nama jabatan pertama */
.animated-box-left.gap-isi-senarai + .bahagian-card{
  margin-top: 24px;   /* ubah ikut selera: 16–40px */
}

/* (Pilihan) Kalau nak semua kotak jabatan ada gap tambahan dari elemen sebelumnya */
.senarai-borang .bahagian-card{
  margin-top: 16px;   /* tambah sedikit jarak konsisten */
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

/* Dot grid neon — BERGERAK KE ATAS PERLAHAN */
.grid-dots{
  position:fixed; inset:0; z-index:0; pointer-events:none;
  background-image:
    radial-gradient(circle at 12px 12px, rgba(0,217,255,.35) 1.2px, transparent 1.3px),
    radial-gradient(circle at 6px 6px, rgba(124,77,255,.25) 1px, transparent 1.1px);
  background-size: 24px 24px, 24px 24px;
  animation: dots-move 80s linear infinite; /* <- perlahan */
  opacity:.28; mix-blend-mode: screen;
}
@keyframes dots-move{
  from{ background-position: 0 180% , 0 120% }
  to  { background-position: 0 -60% , 0 -20% }
}

/* Pastikan konten di atas latar */
.content, .content-dashboard{ position:relative; z-index:1; }

/* --- Content Layout --- */
.content-dashboard {
  padding: 25px 40px;
  display: block !important;
  width: 100%;
  max-width: 100%;
  margin: 0;
  box-sizing: border-box;
}

/* --- Welcome Box --- */
.welcome-box {
  border-radius: 16px;
  padding: 22px 24px;
  margin: 20px auto;
  width: 95%;
  max-width: 1200px;
  color: white;
  font-size: 20px;
  font-weight: 700;
  text-align: center;
  background: linear-gradient(120deg, rgba(0,198,255,.9), rgba(0,114,255,.9), rgba(0,198,255,.9));
  background-size: 200% 200%;
  animation: lightMove 10s ease infinite;
  box-shadow: 0 10px 30px rgba(0,0,0,.35);
  border: 1px solid rgba(160,200,255,.2);
}
@keyframes lightMove {
  0%   { background-position: 0% 50%; }
  50%  { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* --- Statistik Box --- */
.stats-container {
  display:flex; justify-content:center; gap:20px; margin:20px auto 30px auto;
  flex-wrap:wrap; max-width:1200px; width:95%;
}
.stat-box {
  flex:1 1 220px; background: linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.03));
  border-radius: 14px; padding: 18px; text-align:center; 
  box-shadow: 0 8px 24px rgba(0,0,0,.25), inset 0 0 0 1px rgba(255,255,255,.06);
  border:1px solid rgba(120,160,255,.22);
  transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  backdrop-filter: blur(10px);
}
.stat-box:hover { 
  transform: translateY(-5px); 
  box-shadow: 0 14px 30px rgba(0,0,0,.35), 0 0 40px rgba(0,217,255,.12);
  border-color: rgba(0,217,255,.45);
}
.stat-icon {
  width: 50px; height: 50px; border-radius: 12px;
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  color: white; display:flex; align-items:center; justify-content:center;
  font-size: 22px; margin: 0 auto 10px auto;
  box-shadow: 0 6px 16px rgba(0,0,0,.25);
}
.stat-box h3{ margin:8px 0 6px; letter-spacing:.03em }
.stat-box p{ font-size: 28px; font-weight: 800; margin: 0 }

/* --- Button --- */
a.button {
  display:inline-block; padding:10px 20px;
  background: linear-gradient(135deg, #ff6a00, #ee0979);
  color:white; border-radius:10px; text-decoration:none; font-weight:800; letter-spacing:.03em;
  box-shadow: 0 8px 20px rgba(0,0,0,.25);
  transition: transform .12s ease, box-shadow .2s ease;
}
a.button:hover { transform: translateY(-2px) scale(1.03); box-shadow: 0 12px 28px rgba(0,0,0,.35); }

/* --- Animated Box Kecil di Kiri --- */
.animated-box-left {
  border-radius: 12px; padding: 12px 14px; width: 260px;
  background: linear-gradient(145deg, #00bfff, #004aad);
  color: white; font-size: 15px; font-weight: 700; text-align: left;
  box-shadow: 0 8px 20px rgba(0,0,0,.25);
  border:1px solid rgba(160,200,255,.25);
}

/* --- Card Bahagian --- */
.bahagian-card {
  background: linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.03));
  border-radius: 12px; padding: 18px; margin-bottom: 25px;
  box-shadow: 0 8px 22px rgba(0,0,0,.22), inset 0 0 0 1px rgba(255,255,255,.05);
  transition: all .25s ease; border:1px solid rgba(120,160,255,.2);
  backdrop-filter: blur(8px);
}
.bahagian-card:hover {
  background: linear-gradient(135deg, rgba(255,255,255,.10), rgba(255,255,255,.04));
  box-shadow: 0 12px 28px rgba(0,0,0,.3);
  border-color: rgba(0,217,255,.35);
}
details {
  border-radius: 10px; padding: 10px 14px; margin-bottom: 12px; background: rgba(255,255,255,.06);
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
}
summary {
  cursor: pointer; font-weight: 800; font-size: 15px; color: #cfe6ff; display: flex; align-items: center;
}
summary::before {
  content: "▶"; margin-right: 8px; font-size: 13px; transition: transform .2s ease;
}
details[open] summary::before { transform: rotate(90deg); }

/* --- Jadual --- */
.selenggara-box {
  margin-top: 14px; border-radius: 10px; padding: 15px;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
  background: rgba(255,255,255,.04);
}
.selenggara-1 { border-left: 6px solid #00acc1; }
.selenggara-2 { border-left: 6px solid #fb8c00; }
.selenggara-box h4 { margin-bottom: 12px; font-size: 16px; font-weight: 800; color:#e6ffff; }
.selenggara-1 h4 { color:#a8f3ff; }
.selenggara-2 h4 { color:#ffd7b0; }

table { border-collapse: collapse; width: 100%; margin-top: 10px; font-size: 14px; color:#eaf2ff; }
th, td { padding: 10px 12px; border: 1px solid rgba(200,220,255,.18); text-align: center; }
th { background-color: #0f2f66; color: white; }
tr:hover td { background-color: rgba(200,220,255,.06); }

/* Garis pemisah */
hr{ border:0; height:1px; background: linear-gradient(90deg, transparent, rgba(200,220,255,.25), transparent); margin: 20px 0; }

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


/* Hormati pengguna yang tak mahu animasi */
@media (prefers-reduced-motion: reduce){
  .grid-dots{ animation: none; }
  .welcome-box{ animation: none; }
}
    </style>
</head>
<body>

<!-- Grid Dots (latar) -->
<div class="grid-dots"></div>

<button class="toggle-btn" onclick="document.querySelector('.sidebar') && document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<div class="content">
<div class="content content-dashboard">

    <!-- Welcome Box -->
    <div class="welcome-box">
        SISTEM PENYELENGGARAAN BERKALA ICT  - PEGAWAI TEKNIKAL  
        <?= isset($_SESSION['username']) ? ucwords($_SESSION['username']) : '' ?>
        <?php if($tahun !== ""): ?>
            • <span style="font-weight:600; opacity:.9">Tahun: <?= (int)$tahun ?></span>
        <?php endif; ?>
    </div>

    <!-- Statistik Box -->
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon">🛠</div>
            <h3>TELAH DISELENGGARA</h3>
            <p><?= $totalSelenggara ?></p>
        </div>
        <div class="stat-box">
            <div class="stat-icon">✔︎</div>
            <h3>TELAH DISAHKAN</h3>
            <p><?= $totalDisahkan ?></p>
        </div>
        <div class="stat-box">
            <div class="stat-icon">📊</div>
            <h3>JUMLAH PENYELENGGARAAN</h3>
            <p><?= $totalSemua ?></p>
        </div>
    </div>

    <!-- Progress Chart -->
    <div style="
        max-width:650px; margin:30px auto; text-align:center; 
        background: rgba(255,255,255,.06); border:1px solid rgba(160,200,255,.22);
        border-radius:15px; padding:22px 26px; box-shadow:0 10px 24px rgba(0,0,0,.28);
        backdrop-filter: blur(8px);
    ">
        <h3 style="color:#bcd6ff; margin-bottom:18px; letter-spacing:.02em;">Peratusan Status Pengesahan</h3>

        <!-- Progress Bar -->
        <div style="
            position:relative; background:rgba(255,255,255,.08);
            border-radius:30px; height:35px; overflow:hidden; display:flex;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.35);
            border:1px solid rgba(160,200,255,.25);
        ">
            <!-- Telah Disahkan (Hijau - kiri) -->
            <div style="
                width:<?= $peratusDisahkan ?>%;
                background:linear-gradient(90deg,#28a745,#34d058);
                height:100%; transition:width 1s;
            "></div>

            <!-- Belum Disahkan (Merah - kanan) -->
            <div style="
                width:<?= $peratusBelum ?>%;
                background:linear-gradient(90deg,#ff4d4d,#ff1a1a);
                height:100%; transition:width 1s;
            "></div>
        </div>

        <!-- Label bawah bar -->
        <div style="
            display:flex; justify-content:space-between; margin-top:10px;
            font-size:14px; font-weight:800; color:#eaf2ff;
        ">
            <span style="color:#8affb0;">Telah Disahkan (<?= $peratusDisahkan ?>%)</span>
            <span style="color:#ff9a9a;">Belum Disahkan (<?= $peratusBelum ?>%)</span>
        </div>
    </div>

    <!-- Pilih Tahun (center) -->
    <div style="text-align:center; margin:20px 0;">
        <form method="get" 
            style="display:inline-block; padding:12px 18px; border-radius:10px; 
                    background: rgba(255,255,255,.06);
                    border:1px solid rgba(160,200,255,.22);
                    box-shadow:0 8px 20px rgba(0,0,0,.25); max-width:320px; backdrop-filter: blur(6px);">
            <label for="tahun" style="font-weight:700; color:#ffffff; margin-right:8px;">
                Pilih Tahun:
            </label>
            <select name="tahun" id="tahun" onchange="this.form.submit()"
                style="padding:8px 12px; font-size:14px; border-radius:8px; 
                        border:1px solid rgba(160,200,255,.35);
                        background: #ffffff;
                        color:#000; cursor:pointer;">
                <option value="" style="color:#000;">-- Semua Tahun --</option>
                <?php
                $tahunQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(tarikh) AS tahun FROM penyelenggaraan ORDER BY tahun DESC");
                while ($rowTahun = mysqli_fetch_assoc($tahunQuery)) {
                    $val = (int)$rowTahun['tahun'];
                    $selected = ($val == $tahun) ? 'selected' : '';
                    echo "<option value=\"{$val}\" $selected style=\"color:#000;\">{$val}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <hr>

    <!-- Kiri: Isi Borang + Senarai Borang (dibungkus & ada gap bawah) -->
    <div class="senarai-borang">
      <!-- Kiri: Isi Borang -->
      <div style="flex:1; text-align:left;">
        <a href="isi_borang.php" class="button">+ Isi Borang</a>
      </div>

      <!-- Tajuk Senarai Borang (dengan jarak atas) -->
      <div class="animated-box-left gap-isi-senarai">
        <h2 style="margin:0;">SENARAI BORANG</h2>
      </div>

      <?php
      // Loop bahagian — guna query yang dah disediakan awal
      if ($bahagianQuery && mysqli_num_rows($bahagianQuery) > 0):
        while ($bahagian = mysqli_fetch_assoc($bahagianQuery)):
            $nama_bahagian = $bahagian['nama_bahagian'];
            $namaEsc = mysqli_real_escape_string($conn, $nama_bahagian);
      ?>
        <div class="bahagian-card">
          <details>
            <summary><?= htmlspecialchars($nama_bahagian, ENT_QUOTES, 'UTF-8') ?></summary>

            <!-- Bil Selenggara 1 -->
            <div class="selenggara-box selenggara-1">
              <h4>Bil. Selenggara 1</h4>
              <table>
                <tr>
                  <th>Nama Pengguna</th>
                  <th>Juruteknik</th>
                  <th>Bahagian</th>
                  <th>Tarikh</th>
                  <th>Status</th>
                  <th>Cetak</th>
                  <th>Lihat</th>
                  <th>Padam</th>
                </tr>
                <?php
                $result1 = mysqli_query($conn, "SELECT * FROM penyelenggaraan 
                    WHERE nama_bahagian='$namaEsc' AND bil_selenggara='1' $whereTahun 
                    ORDER BY id DESC");
                if ($result1 && mysqli_num_rows($result1) > 0):
                  while ($row = mysqli_fetch_assoc($result1)):
                ?>
                  <tr>
                    <td><?= ucwords($row['nama_pengguna']) ?></td>
                    <td><?= $row['juruteknik'] ?></td>
                    <td><?= $row['nama_bahagian'] ?></td>
                    <td><?= $row['tarikh'] ?></td>
                    <td>
                      <?php if ($row['status'] == 'Disahkan'): ?>
                        <span style="color:#8affb0; font-weight:bold;"><?= $row['status'] ?></span>
                      <?php elseif ($row['status'] == 'Belum Disahkan'): ?>
                        <span style="color:#ff9a9a; font-weight:bold;"><?= $row['status'] ?></span>
                      <?php else: ?>
                        <?= $row['status'] ?>
                      <?php endif; ?>
                    </td>
                    <td><a href="cetak.php?id=<?= (int)$row['id'] ?>" target="_blank" title="Cetak">🖨️</a></td>
                    <td><a href="lihat_borang.php?id=<?= (int)$row['id'] ?>" title="Lihat Borang">🔍</a></td>
                    <td>
                      <a href="delete_borang.php?id=<?= (int)$row['id'] ?>" 
                         onclick="return confirm('Padam borang ini?');" 
                         style="color:#ff8080; text-decoration:none; font-weight:bold;">🗑️</a>
                    </td>
                  </tr>
                <?php endwhile; else: ?>
                  <tr><td colspan="8">Tiada rekod</td></tr>
                <?php endif; ?>
              </table>
            </div>

            <!-- Bil Selenggara 2 -->
            <div class="selenggara-box selenggara-2">
              <h4>Bil. Selenggara 2</h4>
              <table>
                <tr>
                  <th>Nama Pengguna</th>
                  <th>Juruteknik</th>
                  <th>Bahagian</th>
                  <th>Tarikh</th>
                  <th>Status</th>
                  <th>Cetak</th>
                  <th>Lihat</th>
                  <th>Padam</th>
                </tr>
                <?php
                $result2 = mysqli_query($conn, "SELECT * FROM penyelenggaraan 
                    WHERE nama_bahagian='$namaEsc' AND bil_selenggara='2' $whereTahun
                    ORDER BY id DESC");
                if ($result2 && mysqli_num_rows($result2) > 0):
                  while ($row = mysqli_fetch_assoc($result2)):
                ?>
                  <tr>
                    <td><?= ucwords($row['nama_pengguna']) ?></td>
                    <td><?= $row['juruteknik'] ?></td>
                    <td><?= $row['nama_bahagian'] ?></td>
                    <td><?= $row['tarikh'] ?></td>
                    <td>
                      <?php if ($row['status'] == 'Disahkan'): ?>
                        <span style="color:#8affb0; font-weight:bold;"><?= $row['status'] ?></span>
                      <?php elseif ($row['status'] == 'Belum Disahkan'): ?>
                        <span style="color:#ff9a9a; font-weight:bold;"><?= $row['status'] ?></span>
                      <?php else: ?>
                        <?= $row['status'] ?>
                      <?php endif; ?>
                    </td>
                    <td><a href="cetak.php?id=<?= (int)$row['id'] ?>" target="_blank" title="Cetak">🖨️</a></td>
                    <td><a href="lihat_borang.php?id=<?= (int)$row['id'] ?>" title="Lihat Borang">🔍</a></td>
                    <td>
                      <a href="delete_borang.php?id=<?= (int)$row['id'] ?>" 
                         onclick="return confirm('Padam borang ini?');" 
                         style="color:#ff8080; text-decoration:none; font-weight:bold;">🗑️</a>
                    </td>
                  </tr>
                <?php endwhile; else: ?>
                  <tr><td colspan="8">Tiada rekod</td></tr>
                <?php endif; ?>
              </table>
            </div>

          </details>
        </div>
      <?php 
        endwhile;
      else:
      ?>
        <div class="bahagian-card" style="text-align:center; font-weight:700;">
          Tiada senarai borang untuk dipaparkan.
        </div>
      <?php endif; ?>
    </div><!-- /senarai-borang -->

    <?php include 'footer.php'; ?>
</div>
</div>

</body>
</html>
