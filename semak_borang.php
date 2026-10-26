<?php
// semak_borang.php
include 'navigation.php';
include 'db.php';

// dapatkan ID rekod
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// proses sahkan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sahkan'])) {
    $update = mysqli_query($conn, "UPDATE penyelenggaraan SET status='Disahkan' WHERE id=$id");
    if ($update) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'/>
        <style>
          .swal-bright-popup {
            border-radius: 18px !important;
            background: linear-gradient(135deg, #ffffff, #e3f2fd, #f1f8e9);
            box-shadow: 0 0 25px rgba(150, 200, 255, 0.3), inset 0 0 10px rgba(255,255,255,0.4);
            padding: 25px;
            backdrop-filter: blur(6px);
            animation: softGlow 3s infinite alternate;
          }
          @keyframes softGlow {
            from { box-shadow: 0 0 20px rgba(200, 220, 255, 0.3); }
            to   { box-shadow: 0 0 35px rgba(200, 220, 255, 0.6); }
          }
          .swal-bright-title {
            font-family: 'Poppins', sans-serif;
            color: #007BFF;
            font-weight: 600;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(100, 170, 255, 0.4);
          }
          .swal2-html-container { color:#333 !important; font-size:1rem; font-weight:500; }
          .swal2-container {
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(230,245,255,0.8)) !important;
            backdrop-filter: blur(8px);
          }
        </style>
        <script>
        Swal.fire({
          title: '✅ BORANG TELAH DISAHKAN',
          text: 'Rekod berjaya dikemaskini dengan jayanya.',
          icon: 'success',
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true,
          background: 'transparent',
          customClass: { popup: 'swal-bright-popup animate__animated animate__fadeInUp', title: 'swal-bright-title' },
          showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
          hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
        });

        // Redirect ke dashboard_b.php selepas alert habis
        setTimeout(function(){
          window.location.href = 'dashboard_b.php';
        }, 2600);
        </script>";
        exit;
    } else {
        echo "<script>alert('Ralat: Tidak dapat kemaskini rekod');</script>";
    }
}

$result = mysqli_query($conn, "SELECT * FROM penyelenggaraan WHERE id = $id LIMIT 1");
$data   = mysqli_fetch_assoc($result);

if (!$data) { die('Rekod tidak ditemui.'); }

// fungsi tick/pangkah (FIX PARSE ERROR)
function tick($val) {
  return $val == 1 ? "<span style='color:green;font-weight:bold;'>✔</span>"
                   : "<span style='color:red;font-weight:bold;'>❌</span>";
}
?>
<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Semak Borang Penyelenggaraan ICT</title>
  <link rel="stylesheet" href="style.css">

  <style>
  :root{
    --bg-start:#030014;
    --bg-end:#001a37;
    --panel-shadow: 0 14px 34px rgba(0,0,0,.35);
  }

  *{ box-sizing:border-box }
  html,body{ height:100% }

  body{
    margin:0; padding:0; min-height:100vh; overflow-x:hidden;
    color:#e6eeff; font-family:'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    background:
      radial-gradient(1200px 800px at 10% 10%, #0a1a33 0%, transparent 60%),
      radial-gradient(900px 700px at 90% 20%, #0b002b 0%, transparent 70%),
      linear-gradient(160deg, var(--bg-start), var(--bg-end));
  }

  .grid-dots{
    position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image:
      radial-gradient(circle at 12px 12px, rgba(0,217,255,.35) 1.2px, transparent 1.3px),
      radial-gradient(circle at 6px 6px,   rgba(124,77,255,.25) 1px,   transparent 1.1px);
    background-size:24px 24px, 24px 24px;
    animation:dots-move 80s linear infinite;
    opacity:.28; mix-blend-mode:screen;
  }
  @keyframes dots-move{ from{background-position:0 180%, 0 120%} to{background-position:0 -60%, 0 -20%} }

  .content{
    position:relative; z-index:1;
    padding:25px 20px;
    margin-left:250px;
    transition:margin-left .3s ease;
  }
  .sidebar.collapsed ~ .content{ margin-left:80px; }

  .futuristic-legend{
    margin:6px 0 18px; font-weight:800; letter-spacing:.03em; color:#d6e7ff;
  }

  .page-panel{
    width:100%; max-width:1000px; margin:0 auto 40px auto;
    background:#ffffff; color:#111827;
    border:1px solid #e5e7eb; border-radius:16px; box-shadow:var(--panel-shadow);
    padding:22px 24px;
  }

  .form-grid{
    display:grid; grid-template-columns:repeat(2, minmax(0,1fr));
    gap:18px;
  }
  @media (max-width: 992px){ .form-grid{ grid-template-columns:1fr; } }

  fieldset{
    background:#ffffff; color:#111827;
    border:2px solid #6D94C5; border-radius:14px;
    padding:16px 18px; margin:0; box-shadow:0 8px 20px rgba(0,0,0,.08);
  }
  legend{
    padding:0 8px; font-weight:800; color:#0f2f66; letter-spacing:.02em;
  }

  table{ width:100%; border-collapse:collapse; font-size:14px; background:#fff; border-radius:10px; overflow:hidden; }
  td{ padding:10px 12px; border:1px solid #e5e7eb; vertical-align:top; }
  table tr:nth-child(even) td{ background:#fafafb; }

  .toggle-btn{
    position:fixed; top:15px; left:15px; z-index:9999;
    display:inline-flex !important; align-items:center; gap:6px;
    background:#004aad; color:#fff; border:1px solid #004aad;
    border-radius:8px; padding:8px 12px; cursor:pointer; font-size:18px;
    box-shadow:0 6px 16px rgba(0,0,0,.25);
  }
  .toggle-btn:hover{ background:#0066ff; border-color:#0066ff; box-shadow:0 10px 24px rgba(0,0,0,.35); }

  .btn-center{ text-align:center; margin-top:10px; }
  .btn-sahkan{
    padding:12px 25px; font-size:16px; font-weight:bold;
    background:linear-gradient(135deg, #00c853, #43a047);
    border:none; border-radius:8px; color:#fff; cursor:pointer;
    box-shadow:0 4px 10px rgba(0, 200, 83, 0.4);
    transition:all .3s ease;
  }
  .btn-sahkan:hover{
    background:linear-gradient(135deg,#43a047,#2e7d32);
    transform:scale(1.05);
    box-shadow:0 6px 14px rgba(0,150,63,0.5);
  }

  .site-footer{
    width:100%; margin:24px 0 0; padding:16px 20px;
    color:#d6e7ff; background:rgba(255,255,255,0.06);
    border-top:1px solid rgba(160,200,255,.22);
    box-shadow:0 -6px 18px rgba(0,0,0,.25) inset;
  }
  body > .site-footer{ margin-left:250px; transition:margin-left .3s ease; }
  .sidebar.collapsed ~ .content + .site-footer{ margin-left:80px; }
  @media (max-width: 992px){ body > .site-footer{ margin-left:0; } }

  @media (prefers-reduced-motion: reduce){ .grid-dots{ animation:none; } }
  </style>
</head>
<body>

<div class="grid-dots"></div>

<button class="toggle-btn" onclick="
  (function(){
    const s = document.querySelector('.sidebar');
    if(!s) return;
    s.classList.toggle('collapsed');
    const c = document.querySelector('.content');
    if(c) c.style.marginLeft = s.classList.contains('collapsed') ? '80px' : '250px';
    const f = document.querySelector('.site-footer');
    if(f) f.style.marginLeft = s.classList.contains('collapsed') ? '80px' : '250px';
  })()
">☰</button>

<div class="content">
  <h2 class="futuristic-legend">SEMAKAN BORANG PENYELENGGARAAN BERKALA</h2>

  <div class="page-panel">
    <div class="form-wrapper">
      <div class="form-grid">

        <!-- A. MAKLUMAT PENGGUNA -->
        <fieldset class="fieldset-a">
          <legend>A. Maklumat Pengguna</legend>
          <table>
            <tr><td>Nama Bahagian</td><td><?= htmlspecialchars($data['nama_bahagian']); ?></td></tr>
            <tr><td>Nama Pengguna</td><td><?= htmlspecialchars($data['nama_pengguna']); ?></td></tr>
            <tr><td>Tarikh</td><td><?= htmlspecialchars($data['tarikh']); ?></td></tr>
            <tr><td>Juruteknik Bertugas</td><td><?= htmlspecialchars($data['juruteknik']); ?></td></tr>
            <tr><td>Pegawai Pemantau</td><td><?= htmlspecialchars($data['pegawai']); ?></td></tr>
            <tr><td>Sesi Semakan Tahunan</td><td><?= htmlspecialchars($data['sesi_tahunan']); ?></td></tr>
            <tr><td>Bil. Selenggara</td><td><?= htmlspecialchars($data['selenggara']); ?></td></tr>
            <tr><td>Selenggara Kali Ke-</td><td><?= htmlspecialchars($data['bil_selenggara']); ?></td></tr>
          </table>
        </fieldset>

        <!-- B. MAKLUMAT KEMUDAHAN ICT -->
        <fieldset class="fieldset-b">
          <legend>B. Maklumat Kemudahan ICT</legend>
          <table>
            <tr><td>Jenis Kemudahan</td><td><?= htmlspecialchars($data['jenis_kemudahan']); ?></td></tr>
            <tr><td>Model</td><td><?= htmlspecialchars($data['model']); ?></td></tr>
            <tr><td>No. Siri Pendaftaran</td><td><?= htmlspecialchars($data['no_siri']); ?></td></tr>
            <tr><td>Jenis Processor</td><td><?= htmlspecialchars($data['processor']); ?></td></tr>
            <tr><td>Kapasiti RAM</td><td><?= htmlspecialchars($data['ram']); ?></td></tr>
            <tr><td>Kapasiti HDD</td><td><?= htmlspecialchars($data['hdd']); ?></td></tr>
            <tr><td>Jenis & Status Pemacu Cakera</td><td><?= htmlspecialchars($data['optical_drive']); ?></td></tr>
            <tr><td>Sistem Pengoperasi</td><td><?= htmlspecialchars($data['sistem_operasi']); ?></td></tr>
            <tr><td>Alamat IP</td><td><?= htmlspecialchars($data['alamat_ip']); ?></td></tr>
            <tr><td>Jenis & Status Antivirus</td><td><?= htmlspecialchars($data['antivirus']); ?></td></tr>
          </table>
        </fieldset>

        <!-- CPU -->
        <fieldset class="fieldset-cpu">
          <legend>CPU Unit</legend>
          <table>
            <tr><td>Membersihkan Habuk</td><td><?= tick($data['cpu_clean']); ?></td></tr>
            <tr><td>Power Supply</td><td><?= tick($data['cpu_power_supply']); ?></td></tr>
            <tr><td>RAM</td><td><?= tick($data['cpu_ram_sub']); ?></td></tr>
            <tr><td>Processor</td><td><?= tick($data['cpu_processor_sub']); ?></td></tr>
            <tr><td>Papan Induk</td><td><?= tick($data['cpu_motherboard']); ?></td></tr>
            <tr><td>Uji Port</td><td><?= tick($data['cpu_port']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Keyboard -->
        <fieldset class="fieldset-keyboard">
          <legend>Papan Kekunci</legend>
          <table>
            <tr><td>Membersihkan Keyboard</td><td><?= tick($data['kb_clean']); ?></td></tr>
            <tr><td>Uji Setiap Butang</td><td><?= tick($data['kb_test_keys']); ?></td></tr>
            <tr><td>Uji Caps Lock</td><td><?= tick($data['kb_capslock']); ?></td></tr>
            <tr><td>Uji Num Lock</td><td><?= tick($data['kb_numlock']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Mouse -->
        <fieldset class="fieldset-mouse">
          <legend>Tetikus</legend>
          <table>
            <tr><td>Uji Butang Klik</td><td><?= tick($data['mouse_click']); ?></td></tr>
            <tr><td>Uji Scroll</td><td><?= tick($data['mouse_scroll']); ?></td></tr>
            <tr><td>Membersih Track Ball</td><td><?= tick($data['mouse_clean']); ?></td></tr>
          </table>
        </fieldset>

        <!-- HDD -->
        <fieldset class="fieldset-hdd">
          <legend>Cakera Keras</legend>
          <table>
            <tr><td>Bilangan Partition</td><td><?= tick($data['hdd_partition_chk']); ?> <?= htmlspecialchars($data['hdd_partition_val']); ?></td></tr>
            <tr><td>Disk Cleanup</td><td><?= tick($data['hdd_cleanup']); ?></td></tr>
            <tr><td>Scandisk</td><td><?= tick($data['hdd_scandisk']); ?></td></tr>
            <tr><td>Disk Defragmenter</td><td><?= tick($data['hdd_defrag']); ?></td></tr>
            <tr><td>Total Space C:</td><td><?= tick($data['hdd_totalspace_chk']); ?> <?= htmlspecialchars($data['hdd_totalspace_val']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Monitor -->
        <fieldset class="fieldset-monitor">
          <legend>Skrin Paparan (Monitor)</legend>
          <table>
            <tr><td>Membersihkan Skrin</td><td><?= tick($data['monitor_clean']); ?></td></tr>
            <tr><td>Status LED</td><td><?= htmlspecialchars($data['monitor_led_val']); ?></td></tr>
            <tr><td>Uji Skrin Monitor</td><td><?= tick($data['monitor_test_chk']); ?></td></tr>
            <tr><td>Garisan pada Skrin</td><td><?= htmlspecialchars($data['monitor_line_val']); ?></td></tr>
            <tr><td>Titik Pixel Mati</td><td><?= htmlspecialchars($data['monitor_pixel_val']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Printer & Scanner -->
        <fieldset class="fieldset-printer-scanner">
          <legend>Pencetak & Pengimbas</legend>
          <table>
            <tr><td>Membersihkan Pencetak</td><td><?= tick($data['printer_clean']); ?></td></tr>
            <tr><td>Status LED</td><td><?= htmlspecialchars($data['printer_led_val']); ?></td></tr>
            <tr><td>Penyelenggaraan Printer</td><td><?= tick($data['printer_maintenance_chk']); ?></td></tr>
            <tr><td>Roller</td><td><?= tick($data['printer_roller']); ?></td></tr>
            <tr><td>Printhead Cleaning</td><td><?= tick($data['printer_head_clean']); ?></td></tr>
            <tr><td>Printhead Alignment</td><td><?= tick($data['printer_head_align']); ?></td></tr>
            <tr><td>Print Test</td><td><?= tick($data['printer_test']); ?></td></tr>
            <tr><td>Scanner</td><td><?= tick($data['scanner_chk']); ?></td></tr>
            <tr><td>Scan Alignment</td><td><?= tick($data['scanner_align']); ?></td></tr>
            <tr><td>Scan Test</td><td><?= tick($data['scanner_test']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Network -->
        <fieldset class="fieldset-network">
          <legend>Rangkaian (Network)</legend>
          <table>
            <tr><td>10.112.10.21</td><td><?= tick($data['ping_21']); ?> Status: <?= htmlspecialchars($data['status_21']); ?></td></tr>
            <tr><td>10.112.10.22</td><td><?= tick($data['ping_22']); ?> Status: <?= htmlspecialchars($data['status_22']); ?></td></tr>
            <tr><td>10.112.10.140</td><td><?= tick($data['ping_140']); ?> Status: <?= htmlspecialchars($data['status_140']); ?></td></tr>
            <tr><td>10.112.10.34</td><td><?= tick($data['ping_34']); ?> Status: <?= htmlspecialchars($data['status_34']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Windows -->
        <fieldset class="fieldset-windows">
          <legend>Sistem Pengoperasi (Windows)</legend>
          <table>
            <tr><td>Windows Update</td><td><?= tick($data['win_update']); ?></td></tr>
            <tr><td>Antivirus Update</td><td><?= tick($data['win_antivirus_update']); ?></td></tr>
            <tr><td>Antivirus Scan</td><td><?= tick($data['win_antivirus_scan']); ?></td></tr>
            <tr><td>Driver Update</td><td><?= tick($data['win_driver_update']); ?></td></tr>
            <tr><td>Directory Repair</td><td><?= tick($data['win_dir_repair']); ?></td></tr>
            <tr><td>Recycle Bin</td><td><?= tick($data['win_recyclebin']); ?></td></tr>
            <tr><td>Restart</td><td><?= tick($data['win_restart']); ?></td></tr>
            <tr><td>Default BIOS</td><td><?= tick($data['win_bios_default']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Browser -->
        <fieldset class="fieldset-browser">
          <legend>Pelayar Web</legend>
          <table>
            <tr><td>Clear History</td><td><?= tick($data['browser_clear_history']); ?></td></tr>
            <tr><td>Homepage (www.mdtm.gov.my)</td><td><?= tick($data['browser_homepage']); ?></td></tr>
            <tr><td>Plugin Update</td><td><?= tick($data['browser_plugin_update']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Apps Umum -->
        <fieldset class="fieldset-app">
          <legend>Sistem Aplikasi Umum</legend>
          <table>
            <tr><td>Microsoft Office</td><td><?= tick($data['app_office_main']); ?></td></tr>
            <tr><td>Word</td><td><?= tick($data['app_office_word']); ?></td></tr>
            <tr><td>Excel</td><td><?= tick($data['app_office_excel']); ?></td></tr>
            <tr><td>Powerpoint</td><td><?= tick($data['app_office_powerpoint']); ?></td></tr>
            <tr><td>Antivirus</td><td><?= tick($data['app_antivirus_chk']); ?> Jenis: <?= htmlspecialchars($data['app_antivirus_val']); ?></td></tr>
            <tr><td>Autocad</td><td><?= tick($data['app_autocad']); ?></td></tr>
            <tr><td>Mapinfo</td><td><?= tick($data['app_mapinfo']); ?></td></tr>
            <tr><td>Adobe Acrobat</td><td><?= tick($data['app_adobe_acrobat']); ?></td></tr>
            <tr><td>Adobe Photoshop</td><td><?= tick($data['app_adobe_photoshop']); ?></td></tr>
            <tr><td>Winzip</td><td><?= tick($data['app_winzip']); ?></td></tr>
            <tr><td>Firefox</td><td><?= tick($data['app_firefox']); ?></td></tr>
          </table>
        </fieldset>

        <!-- Apps Khusus -->
        <fieldset class="fieldset-khusus">
          <legend>Sistem Aplikasi Khusus</legend>
          <table>
            <tr>
              <td>e-PBT <?= tick($data['khusus_epbt_main']); ?></td>
              <td>Ping 10.112.10.34: <?= tick($data['khusus_epbt_ping']); ?> &nbsp; | &nbsp; Fungsi: <?= tick($data['khusus_epbt_func']); ?></td>
            </tr>
            <tr>
              <td>e-Hadir <?= tick($data['khusus_ehadir_main']); ?></td>
              <td>Ping 10.112.10.140: <?= tick($data['khusus_ehadir_ping']); ?> &nbsp; | &nbsp; Uji URL: <?= tick($data['khusus_ehadir_url']); ?> &nbsp; | &nbsp; Fungsi: <?= tick($data['khusus_ehadir_func']); ?></td>
            </tr>
            <tr>
              <td>eSPK (Penguatkuasaan) <?= tick($data['khusus_espk_main']); ?></td>
              <td>Ping 10.112.10.7: <?= tick($data['khusus_espk_ping']); ?> &nbsp; | &nbsp; Uji URL: <?= tick($data['khusus_espk_url']); ?> &nbsp; | &nbsp; Fungsi: <?= tick($data['khusus_espk_func']); ?></td>
            </tr>
            <tr>
              <td>HrMIS <?= tick($data['khusus_hrmis_main']); ?></td>
              <td>Fungsi: <?= tick($data['khusus_hrmis_func']); ?></td>
            </tr>
          </table>
        </fieldset>

      </div><!-- /.form-grid -->

      <!-- butang sahkan / mesej disahkan -->
      <form method="post">
        <div class="btn-center">
        <?php if ($data['status'] != 'Disahkan'): ?>
          <button type="submit" name="sahkan" class="btn-sahkan">✅ SAHKAN BORANG</button>
        <?php else: ?>
          <p style="color:green; font-weight:bold; margin-top:20px;">
            ✅ Borang ini telah disahkan oleh Ts.Shahzan Hafiz Bin Zainal Abidin
          </p>
        <?php endif; ?>
        </div>
      </form>

    </div><!-- /.form-wrapper -->
  </div><!-- /.page-panel -->
</div><!-- /.content -->

<?php include 'footer.php'; ?>

</body>
</html>
