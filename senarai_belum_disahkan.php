<?php
include 'auth.php';
include 'db.php';
include 'navigation.php';

/*
  OPTIONAL (kalau db.php tak select database mainict):
  Uncomment kalau perlu paksa pilih DB mainict.
*/
// mysqli_select_db($conn, "mainict");

// Ambil tahun dari URL (GET) jika ada
$tahun = "";
if (isset($_GET['tahun'])) {
    $tahun = intval($_GET['tahun']);
    if ($tahun <= 0) { $tahun = ""; }
}

// Syarat ikut tahun (optional, ikut gaya dashboard)
$whereTahun = ($tahun !== "") 
  ? "AND YEAR(tarikh) = '".mysqli_real_escape_string($conn, $tahun)."'" 
  : "";

// Query semua status belum disahkan sahaja
$q = mysqli_query($conn, "
  SELECT * FROM penyelenggaraan
  WHERE status='Belum Disahkan' $whereTahun
  ORDER BY id DESC
");

$total = ($q) ? mysqli_num_rows($q) : 0;

// Back link (kekal tahun jika ada)
$back = "dashboard_b.php";
if ($tahun !== "") {
  $back .= "?tahun=".(int)$tahun;
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <title>Senarai Belum Disahkan - ICT MDTM</title>
  <link rel="stylesheet" href="style.css">

  <style>
:root{
  --bg-start:#030014;
  --bg-end:#001a37;
  --neon:#00d9ff;
  --neon-2:#7c4dff;
  --panel:#0b1020cc;
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

/* Pastikan konten di atas latar */
.content, .content-dashboard, .welcome-box, .card { position: relative; z-index: 1; }

.content-dashboard{ padding:25px 40px; width:100%; max-width:100%; }

.welcome-box{
  border-radius:16px; padding:22px 24px; margin:20px auto; width:95%; max-width:1200px;
  color:white; font-size:20px; font-weight:700; text-align:center;
  background: linear-gradient(120deg, rgba(0,198,255,.9), rgba(0,114,255,.9), rgba(0,198,255,.9));
  background-size: 200% 200%; animation: lightMove 10s ease infinite;
  box-shadow: 0 10px 30px rgba(0,0,0,.35); border:1px solid rgba(160,200,255,.2);
}
@keyframes lightMove{
  0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%}
}

.card{
  width:95%; max-width:1200px; margin:0 auto 20px auto;
  background: linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.03));
  border-radius:14px; padding:18px;
  box-shadow: 0 8px 24px rgba(0,0,0,.25), inset 0 0 0 1px rgba(255,255,255,.06);
  border:1px solid rgba(120,160,255,.22);
  backdrop-filter: blur(10px);
}

.actions-top{
  display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;
  align-items:center; margin-bottom:12px;
}

.btn{
  display:inline-block;
  background:#004aad;
  color:#fff;
  border:1px solid #004aad;
  padding:10px 14px;
  border-radius:10px;
  text-decoration:none;
  font-weight:800;
  box-shadow: 0 8px 20px rgba(0,0,0,.25);
}
.btn:hover{ background:#0066ff; border-color:#0066ff; }

.badge{
  display:inline-block;
  padding:8px 12px;
  border-radius:999px;
  background: rgba(255,255,255,.08);
  border:1px solid rgba(160,200,255,.22);
  font-weight:900;
  color:#eaf2ff;
}

.filter-wrap{
  display:inline-block; padding:10px 14px; border-radius:10px;
  background: rgba(255,255,255,.06);
  border:1px solid rgba(160,200,255,.22);
  box-shadow:0 8px 20px rgba(0,0,0,.25);
  backdrop-filter: blur(6px);
}
.filter-wrap label{ font-weight:800; color:#ffffff; margin-right:8px; }
.filter-wrap select{
  padding:8px 12px; font-size:14px; border-radius:8px;
  border:1px solid rgba(160,200,255,.35);
  background:#ffffff; color:#000; cursor:pointer;
}

table{ border-collapse: collapse; width:100%; margin-top:10px; font-size:14px; color:#eaf2ff; }
th,td{ padding:10px 12px; border:1px solid rgba(200,220,255,.18); text-align:center; }
th{ background-color:#0f2f66; color:white; }
tr:hover td{ background-color: rgba(200,220,255,.06); }

.status-belum{ color:#ff9a9a; font-weight:900; }

.toggle-btn{
  position: fixed; top:15px; left:15px; z-index: 9999;
  display: inline-flex !important; align-items:center; gap:6px;
  background: #004aad; color: #fff; border: 1px solid #004aad;
  border-radius: 8px; padding: 8px 12px; cursor: pointer; font-size:18px;
  box-shadow: 0 6px 16px rgba(0,0,0,.25);
}
.toggle-btn:hover{ background:#0066ff; border-color:#0066ff; box-shadow:0 10px 24px rgba(0,0,0,.35); }

hr{ border:0; height:1px; background: linear-gradient(90deg, transparent, rgba(200,220,255,.25), transparent); margin: 18px 0; }

@media (prefers-reduced-motion: reduce){
  .grid-dots{ animation: none; }
  .welcome-box{ animation: none; }
}
  </style>
</head>
<body>

<div class="grid-dots"></div>

<button class="toggle-btn" onclick="document.querySelector('.sidebar') && document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<div class="content">
  <div class="content-dashboard">

    <div class="welcome-box">
      SENARAI PENYELENGGARAAN • STATUS: BELUM DISAHKAN
      <?php if($tahun !== ""): ?>
        • <span style="font-weight:600; opacity:.9">Tahun: <?= (int)$tahun ?></span>
      <?php endif; ?>
    </div>

    <div class="card">

      <div class="actions-top">
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
          <a class="btn" href="<?= htmlspecialchars($back, ENT_QUOTES, 'UTF-8') ?>">← Kembali Dashboard</a>
          <span class="badge">Jumlah: <?= (int)$total ?></span>
        </div>

        <!-- Filter Tahun (optional) -->
        <form method="get" class="filter-wrap">
          <label for="tahun">Pilih Tahun:</label>
          <select name="tahun" id="tahun" onchange="this.form.submit()">
            <option value="">-- Semua Tahun --</option>
            <?php
            $tahunQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(tarikh) AS tahun FROM penyelenggaraan ORDER BY tahun DESC");
            while ($rowTahun = mysqli_fetch_assoc($tahunQuery)) {
                $val = (int)$rowTahun['tahun'];
                $selected = ($val == $tahun) ? 'selected' : '';
                echo "<option value=\"{$val}\" $selected>{$val}</option>";
            }
            ?>
          </select>
        </form>
      </div>

      <hr>

      <table>
        <tr>
          <th>Nama Pengguna</th>
          <th>Juruteknik</th>
          <th>Bahagian</th>
          <th>Bil. Selenggara</th>
          <th>Tarikh</th>
          <th>Status</th>
          <th>Cetak</th>
          <th>Semak / Sahkan</th>
          <th>Padam</th>
        </tr>

        <?php if ($q && mysqli_num_rows($q) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($q)): ?>
            <tr>
              <td><?= ucwords($row['nama_pengguna']) ?></td>
              <td><?= htmlspecialchars($row['juruteknik'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['nama_bahagian'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['bil_selenggara'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars($row['tarikh'], ENT_QUOTES, 'UTF-8') ?></td>
              <td class="status-belum"><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></td>

              <td><a href="cetak.php?id=<?= (int)$row['id'] ?>" target="_blank" title="Cetak">🖨️</a></td>

              <!-- Link terus ke semak_borang.php -->
              <td>
                <a href="semak_borang.php?id=<?= (int)$row['id'] ?>" title="Semak / Sahkan">✅</a>
              </td>

              <td>
                <a href="delete_borang.php?id=<?= (int)$row['id'] ?>"
                   onclick="return confirm('Padam borang ini?');"
                   style="color:#ff8080; text-decoration:none; font-weight:bold;"
                   title="Padam">
                  🗑️
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9">Tiada rekod “Belum Disahkan”.</td>
          </tr>
        <?php endif; ?>
      </table>

    </div>

    <?php include 'footer.php'; ?>

  </div>
</div>

</body>
</html>
