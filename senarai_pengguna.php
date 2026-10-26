<?php
include 'auth.php';
include 'db.php';
include 'navigation.php';

// ==========================
// A. Query Data Pengguna
// ==========================
$sql    = "SELECT * FROM pengguna_jabatan ORDER BY jabatan";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

// Group ikut jabatan
$jabatanData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $jabatanData[$row['jabatan']][] = $row;
}

// Kira statistik
$totalJabatan  = count($jabatanData);
$totalPengguna = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pengguna_jabatan"));

// Data untuk chart
$jabatanLabels = [];
$jabatanCounts = [];
foreach ($jabatanData as $jabatan => $users) {
    $jabatanLabels[] = $jabatan;
    $jabatanCounts[] = count($users);
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<title>Senarai Pengguna</title>
<link rel="stylesheet" href="style.css">
<style>
/* ===== Tema latar gelap futuristik (selaras daftar_pengguna.php) ===== */
:root{
  --bg-start:#030014; /* deep space */
  --bg-end:#001a37;   /* deep blue */
}

*{ box-sizing:border-box }
html,body{ height:100% }
body{
  background:
    radial-gradient(1200px 800px at 10% 10%, #0a1a33 0%, transparent 60%),
    radial-gradient(900px 700px at 90% 20%, #0b002b 0%, transparent 70%),
    linear-gradient(160deg, var(--bg-start), var(--bg-end));
  margin:0; padding:0; min-height:100vh; overflow-x:hidden;
  font-family: Arial, sans-serif;
  color:#e6eeff;
}

/* Grid dots neon — bergerak ke atas perlahan (sama gaya) */
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

/* Matikan visual particles tetapi kekalkan struktur asal (jika masih dipanggil) */
#particles-js{ display:none !important; }

/* Layering kandungan */
.content { position: relative; z-index: 1; padding: 25px 40px; transition: margin-left 0.3s ease; margin-left: 250px; }
.content-dashboard { width: 100%; max-width: 1200px; margin: 0 auto; }

/* Sidebar (asal) */
.sidebar { width: 250px; position: fixed; top: 0; left: 0; height: 100%; transition: all 0.3s ease; z-index:1000; }
.sidebar.collapsed { width: 0px; }

/* ===== [DITAMBAH] Sidebar toggle button — sentiasa biru ===== */
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

/* Kad Statistik (asal, tweak warna teks agar sesuai tema gelap) */
.stats-container { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
.stat-card {
  flex: 1; min-width: 200px;
  background: linear-gradient(135deg, rgba(255,255,255,.1), rgba(255,255,255,.05));
  border-radius: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.35);
  padding: 20px; text-align: center; transition: all 0.3s ease;
  border:1px solid rgba(160,200,255,.22);
}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 24px rgba(0,0,0,0.45); }
.stat-card h3 { font-size: 28px; color: #bcd6ff; margin: 0; }
.stat-card p  { font-size: 16px; margin: 5px 0 0; color: #e6eeff; opacity:.85; }

/* Carta Donut (asal, gelapkan container sedikit) */
.chart-wrapper {
  display: flex; justify-content: center; align-items: center; gap: 40px; flex-wrap: wrap;
  background: rgba(255,255,255,.06); border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.35);
  padding: 30px; margin-bottom: 40px; flex-direction: column;
  border:1px solid rgba(160,200,255,.22);
}
.chart-title { text-align: center; font-weight: bold; font-size: 22px; color: #d6e7ff; letter-spacing: 1px; margin-bottom: 10px; }
.chart-area { display: flex; justify-content: center; align-items: center; gap: 40px; flex-wrap: wrap; }
.chart-box { width: 280px; height: 280px; }
.chart-legend { text-align: left; }
.chart-legend ul { list-style: none; padding: 0; margin: 0; }
.chart-legend li { display: flex; align-items: center; margin-bottom: 8px; font-size: 14px; color: #e6eeff; }
.legend-color { width: 16px; height: 16px; border-radius: 4px; margin-right: 10px; }

/* Kad Jabatan (asal, gelapkan sikit) */
.jabatan-card {
  background: linear-gradient(135deg, rgba(255,255,255,.10), rgba(255,255,255,.05));
  border-radius: 12px; padding: 18px; margin-bottom: 25px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.35); transition: all 0.3s ease;
  border:1px solid rgba(160,200,255,.22);
}

/* Dropdown (asal, tapi sesuaikan warna) */
.dropdown-btn{
  width: 100%; text-align: left; padding: 12px 16px; font-weight: bold; font-size: 15px; color: #cfe6ff;
  border: none; background: rgba(255,255,255,.08); border-radius: 8px; cursor: pointer;
  box-shadow: 0 2px 8px rgba(0,0,0,0.25);
}
.dropdown-content{ display:none; margin-top:12px; overflow-x:auto; }
.dropdown-content table{
  width:70%; border-collapse: collapse; font-size:14px; margin:10px auto;
  background: rgba(10,20,40,.6); color:#e6eeff;
  border-radius: 10px; overflow:hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.35);
  border:1px solid rgba(160,200,255,.22);
}
.dropdown-content th{
  background: #0f2f66; color: white; padding:12px; font-size:15px; text-transform: uppercase;
}
.dropdown-content td{
  padding:12px; border-bottom: 1px solid rgba(200,220,255,.18); text-align:center; color:#e6eeff;
}
.dropdown-content tr:nth-child(even) td { background: rgba(255,255,255,.05); }
.dropdown-content tr:hover td { background: rgba(200,220,255,.08); transition:0.2s ease-in-out; }

/* Butang delete kekal */
.btn-delete{
  background: #e63946; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 13px;
  cursor: pointer; transition: all 0.2s ease;
}
.btn-delete:hover { background: #cc0000; transform: scale(1.05); }
</style>
</head>
<body>

<!-- Grid dots latar (baru, selaras daftar_pengguna.php) -->
<div class="grid-dots"></div>

<div id="particles-js"></div> <!-- dikekalkan tapi disembunyikan oleh CSS -->

<!-- [DITAMBAH] Butang toggle sidebar -->
<button class="toggle-btn" onclick="document.querySelector('.sidebar') && document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<div class="content">
  <div class="content-dashboard">
    <h2 class="futuristic-legend">SENARAI PENGGUNA</h2>

    <!-- Kad Statistik -->
    <div class="stats-container">
      <div class="stat-card">
        <h3><?= $totalJabatan ?></h3>
        <p>Jumlah Jabatan</p>
      </div>
      <div class="stat-card">
        <h3><?= $totalPengguna ?></h3>
        <p>Jumlah Pengguna</p>
      </div>
    </div>

    <!-- Carta Donut -->
    <div class="chart-wrapper">
      <div class="chart-title">AGIHAN PENGGUNA MENGIKUT JABATAN</div>
      <div class="chart-area">
        <div class="chart-box">
          <canvas id="donutChart"></canvas>
        </div>
        <div class="chart-legend">
          <ul id="legendList"></ul>
        </div>
      </div>
    </div>

    <!-- Senarai Pengguna -->
    <?php foreach ($jabatanData as $jabatan => $users): ?>
      <div class="jabatan-card">
        <button class="dropdown-btn" onclick="toggleDropdown('drop-<?= md5($jabatan) ?>')">
          <?= htmlspecialchars($jabatan) ?> ▼
        </button>
        <div id="drop-<?= md5($jabatan) ?>" class="dropdown-content">
          <table>
            <tr>
              <th>ID</th>
              <th>Jabatan</th>
              <th>Nama Pengguna</th>
              <th>Tindakan</th>
            </tr>
            <?php foreach ($users as $row): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                <td>
                  <form method="post" action="padam_pengguna.php" onsubmit="return confirm('Padam pengguna ini?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn-delete">🗑️ Padam</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($jabatanLabels) ?>;
const counts = <?= json_encode($jabatanCounts) ?>;
const colors = [
  '#004aad','#0077ff','#00b4d8','#90e0ef','#caf0f8',
  '#00509d','#003566','#669bbc','#89c2d9','#e0f7ff'
];

// Donut Chart
const donutChart = new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: labels,
    datasets: [{ data: counts, backgroundColor: colors }]
  },
  options: {
    cutout: '60%',
    animation: { animateScale: true, animateRotate: true },
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.formattedValue;
            const words = label.match(/.{1,20}/g) || [];
            words[words.length - 1] += `: ${value}`;
            return words;
          }
        }
      }
    }
  }
});

// Bina legend manual di tepi
const legendContainer = document.getElementById('legendList');
labels.forEach((label, i) => {
  const li = document.createElement('li');
  const box = document.createElement('span');
  box.className = 'legend-color';
  box.style.backgroundColor = colors[i % colors.length];
  li.appendChild(box);
  li.appendChild(document.createTextNode(`${label} (${counts[i]})`));
  legendContainer.appendChild(li);
});

function toggleDropdown(id) {
  var el = document.getElementById(id);
  el.style.display = (el.style.display === "block") ? "none" : "block";
}

// Kekalkan logik toggle sidebar asal
const toggleBtn = document.querySelector('.toggle-btn');
const content   = document.querySelector('.content');
const sidebar   = document.querySelector('.sidebar');
if (toggleBtn && content && sidebar){
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    content.style.marginLeft = sidebar.classList.contains('collapsed') ? '80px' : '250px';
  });
}
</script>

<!-- Biarkan skrip particles wujud tapi tidak kelihatan (CSS display:none) -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
if (false) { /* dimatikan */ }
</script>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
<!-- SweetAlert2 + Animate.css -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'/>

<style>
.swal-gradient-popup {
  border-radius: 18px !important;
  background: linear-gradient(135deg, #ffffff, #e3f2fd, #f1f8e9);
  box-shadow: 0 0 25px rgba(150, 200, 255, 0.4), inset 0 0 10px rgba(255,255,255,0.3);
  padding: 25px; backdrop-filter: blur(8px);
}
.swal-gradient-title { font-family: 'Poppins', sans-serif; color: #007BFF; font-weight: 600; font-size: 1.6rem; }
.swal2-html-container { color: #333 !important; font-size: 1rem; font-weight: 500; }
.swal2-container { background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(235,245,255,0.85)) !important; backdrop-filter: blur(8px); }
</style>

<script>
Swal.fire({
  title: '🗑️ PENGGUNA DIPADAM',
  text: 'Rekod pengguna telah dipadam dengan jayanya!',
  icon: 'success',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
  customClass: {
    popup: 'swal-gradient-popup animate__animated animate__fadeInUp',
    title: 'swal-gradient-title'
  },
  showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
  hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
}).then(() => {
  if (window.location.search.includes('deleted=1')) {
    const cleanURL = window.location.origin + window.location.pathname;
    window.location.replace(cleanURL);
  }
});
</script>
<?php endif; ?>

</body>
</html>
