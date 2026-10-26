<?php
// isi_borang.php
include 'db.php';

// auto set tarikh hari ini
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ----------------- A. MAKLUMAT PENGGUNA -----------------
    $nama_bahagian  = isset($_POST['nama_bahagian']) ? strtoupper(mysqli_real_escape_string($conn, $_POST['nama_bahagian'])) : '';
    $nama_pengguna  = isset($_POST['nama_pengguna']) ? strtoupper(mysqli_real_escape_string($conn, $_POST['nama_pengguna'])) : '';
    $tarikh         = isset($_POST['tarikh']) ? mysqli_real_escape_string($conn, $_POST['tarikh']) : $today;

    // >>> UBAH: juruteknik dropdown (dua pilihan sahaja)
    $juruteknik = isset($_POST['juruteknik']) ? mysqli_real_escape_string($conn, $_POST['juruteknik']) : 'Mohd Hafiz Bin Tajuddin';
    $allowed_juruteknik = ['Mohd Hafiz Bin Tajuddin', 'Zaki Bin Abdul Naim'];
    if (!in_array($juruteknik, $allowed_juruteknik, true)) {
        $juruteknik = 'Mohd Hafiz Bin Tajuddin';
    }

    $pegawai        = "Ts. Shahzan Hafiz Bin Zainal Abidin"; // tetap
    $sesi_tahunan   = isset($_POST['sesi_tahunan']) ? mysqli_real_escape_string($conn, $_POST['sesi_tahunan']) : '';
    $selenggara   = isset($_POST['selenggara']) ? mysqli_real_escape_string($conn, $_POST['selenggara']) : '';
    $bil_selenggara = isset($_POST['bil_selenggara']) ? mysqli_real_escape_string($conn, $_POST['bil_selenggara']) : '';

    // ----------------- B. MAKLUMAT KEMUDAHAN ICT -----------------
    $jenis_kemudahan = isset($_POST['jenis_kemudahan']) ? mysqli_real_escape_string($conn, $_POST['jenis_kemudahan']) : '';
    $model           = isset($_POST['model']) ? mysqli_real_escape_string($conn, $_POST['model']) : '';
    $no_siri         = isset($_POST['no_siri']) ? mysqli_real_escape_string($conn, $_POST['no_siri']) : '';
    $processor       = isset($_POST['processor']) ? mysqli_real_escape_string($conn, $_POST['processor']) : '';
    $ram             = isset($_POST['ram']) ? mysqli_real_escape_string($conn, $_POST['ram']) : '';
    $hdd             = isset($_POST['hdd']) ? mysqli_real_escape_string($conn, $_POST['hdd']) : '';
    $optical_drive   = isset($_POST['optical_drive']) ? mysqli_real_escape_string($conn, $_POST['optical_drive']) : '';
    $os              = isset($_POST['os']) ? mysqli_real_escape_string($conn, $_POST['os']) : '';
    $ip              = isset($_POST['ip']) ? mysqli_real_escape_string($conn, $_POST['ip']) : '';
    $antivirus       = isset($_POST['antivirus']) ? mysqli_real_escape_string($conn, $_POST['antivirus']) : '';

    // ----------------- C. MAKLUMAT PENCETAK -----------------
    $jenis_pencetak  = isset($_POST['jenis_pencetak']) ? mysqli_real_escape_string($conn, $_POST['jenis_pencetak']) : '';
    $ups             = isset($_POST['ups']) ? mysqli_real_escape_string($conn, $_POST['ups']) : '';
    $aksesori        = isset($_POST['aksesori']) ? mysqli_real_escape_string($conn, $_POST['aksesori']) : '';

    // ----------------- CPU Unit -----------------
    $cpu_clean        = isset($_POST['cpu_clean']) ? 1 : 0;
    $cpu_power_supply = $cpu_clean ? 1 : 0;
    $cpu_ram_sub      = $cpu_clean ? 1 : 0;
    $cpu_processor_sub= $cpu_clean ? 1 : 0;
    $cpu_motherboard  = $cpu_clean ? 1 : 0;
    $cpu_port         = $cpu_clean ? 1 : 0;

    // ----------------- Keyboard -----------------
    $kb_clean     = isset($_POST['kb_clean']) ? 1 : 0;
    $kb_test_keys = isset($_POST['kb_test_keys']) ? 1 : 0;
    $kb_capslock  = isset($_POST['kb_capslock']) ? 1 : 0;
    $kb_numlock   = isset($_POST['kb_numlock']) ? 1 : 0;

    // ----------------- Mouse -----------------
    $mouse_click  = isset($_POST['mouse_click']) ? 1 : 0;
    $mouse_scroll = isset($_POST['mouse_scroll']) ? 1 : 0;
    $mouse_clean  = isset($_POST['mouse_clean']) ? 1 : 0;

    // ----------------- Hard Disk -----------------
    $hdd_partition_chk = isset($_POST['hdd_partition_chk']) ? 1 : 0;
    $hdd_partition_val = isset($_POST['hdd_partition_val']) ? mysqli_real_escape_string($conn, $_POST['hdd_partition_val']) : '';
    $hdd_cleanup       = isset($_POST['hdd_cleanup']) ? 1 : 0;
    $hdd_scandisk      = isset($_POST['hdd_scandisk']) ? 1 : 0;
    $hdd_defrag        = isset($_POST['hdd_defrag']) ? 1 : 0;
    $hdd_totalspace_chk= isset($_POST['hdd_totalspace_chk']) ? 1 : 0;
    $hdd_totalspace_val= isset($_POST['hdd_totalspace_val']) ? mysqli_real_escape_string($conn, $_POST['hdd_totalspace_val']) : '';

    // ----------------- Monitor -----------------
    $monitor_clean    = isset($_POST['monitor_clean']) ? 1 : 0;
    $monitor_led_chk  = isset($_POST['monitor_led_chk']) ? 1 : 0;
    $monitor_led_val  = isset($_POST['monitor_led_val']) ? mysqli_real_escape_string($conn, $_POST['monitor_led_val']) : '';
    $monitor_test_chk = isset($_POST['monitor_test_chk']) ? 1 : 0;
    $monitor_line_val = isset($_POST['monitor_line_val']) ? mysqli_real_escape_string($conn, $_POST['monitor_line_val']) : '';
    $monitor_pixel_val= isset($_POST['monitor_pixel_val']) ? mysqli_real_escape_string($conn, $_POST['monitor_pixel_val']) : '';

    // ----------------- Printer & Scanner -----------------
    $printer_clean      = isset($_POST['printer_clean']) ? 1 : 0;
    $printer_led_chk    = isset($_POST['printer_led_chk']) ? 1 : 0;
    $printer_led_val    = isset($_POST['printer_led_val']) ? mysqli_real_escape_string($conn, $_POST['printer_led_val']) : '';
    $printer_maintenance_chk = isset($_POST['printer_maintenance_chk']) ? 1 : 0;
    $printer_roller     = $printer_maintenance_chk ? 1 : 0;
    $printer_head_clean = $printer_maintenance_chk ? 1 : 0;
    $printer_head_align = $printer_maintenance_chk ? 1 : 0;
    $printer_test       = $printer_maintenance_chk ? 1 : 0;
    $scanner_chk        = isset($_POST['scanner_chk']) ? 1 : 0;
    $scanner_align      = $scanner_chk ? 1 : 0;
    $scanner_test       = $scanner_chk ? 1 : 0;

    // ----------------- Network (Ping) -----------------
    $ping_21   = isset($_POST['ping_21']) ? 1 : 0;
    $status_21 = isset($_POST['status_21']) ? mysqli_real_escape_string($conn, $_POST['status_21']) : '';
    $ping_22   = isset($_POST['ping_22']) ? 1 : 0;
    $status_22 = isset($_POST['status_22']) ? mysqli_real_escape_string($conn, $_POST['status_22']) : '';
    $ping_140  = isset($_POST['ping_140']) ? 1 : 0;
    $status_140= isset($_POST['status_140']) ? mysqli_real_escape_string($conn, $_POST['status_140']) : '';
    $ping_34   = isset($_POST['ping_34']) ? 1 : 0;
    $status_34 = isset($_POST['status_34']) ? mysqli_real_escape_string($conn, $_POST['status_34']) : '';

    // ----------------- Sistem Pengoperasi -----------------
    $win_update        = isset($_POST['win_update']) ? 1 : 0;
    $win_antivirus_update = isset($_POST['win_antivirus_update']) ? 1 : 0;
    $win_antivirus_scan   = isset($_POST['win_antivirus_scan']) ? 1 : 0;
    $win_driver_update    = isset($_POST['win_driver_update']) ? 1 : 0;
    $win_dir_repair       = isset($_POST['win_dir_repair']) ? 1 : 0;
    $win_recyclebin       = isset($_POST['win_recyclebin']) ? 1 : 0;
    $win_restart          = isset($_POST['win_restart']) ? 1 : 0;
    $win_bios_default     = isset($_POST['win_bios_default']) ? 1 : 0;

    // ----------------- Pelayar Web -----------------
    $browser_clear_history = isset($_POST['browser_clear_history']) ? 1 : 0;
    $browser_homepage      = isset($_POST['browser_homepage']) ? 1 : 0;
    $browser_plugin_update = isset($_POST['browser_plugin_update']) ? 1 : 0;

    // ----------------- Sistem Aplikasi Umum -----------------
    $app_office_main  = isset($_POST['app_office_main']) ? 1 : 0;
    $app_office_word  = $app_office_main ? 1 : 0;
    $app_office_excel = $app_office_main ? 1 : 0;
    $app_office_powerpoint = $app_office_main ? 1 : 0;
    $app_antivirus_chk = isset($_POST['app_antivirus_chk']) ? 1 : 0;
    $app_antivirus_val = isset($_POST['app_antivirus_val']) ? mysqli_real_escape_string($conn, $_POST['app_antivirus_val']) : '';
    $app_autocad       = isset($_POST['app_autocad']) ? 1 : 0;
    $app_mapinfo       = isset($_POST['app_mapinfo']) ? 1 : 0;
    $app_adobe_main    = isset($_POST['app_adobe_main']) ? 1 : 0;
    $app_adobe_acrobat   = $app_adobe_main ? 1 : 0;
    $app_adobe_photoshop = $app_adobe_main ? 1 : 0;
    $app_winzip        = isset($_POST['app_winzip']) ? 1 : 0;
    $app_firefox       = isset($_POST['app_firefox']) ? 1 : 0;

    // ----------------- Sistem Aplikasi Khusus -----------------
    $khusus_epbt_main = isset($_POST['khusus_epbt_main']) ? 1 : 0;
    $khusus_epbt_ping = $khusus_epbt_main ? 1 : 0;
    $khusus_epbt_func = $khusus_epbt_main ? 1 : 0;

    $khusus_ehadir_main = isset($_POST['khusus_ehadir_main']) ? 1 : 0;
    $khusus_ehadir_ping = $khusus_ehadir_main ? 1 : 0;
    $khusus_ehadir_url  = $khusus_ehadir_main ? 1 : 0;
    $khusus_ehadir_func = $khusus_ehadir_main ? 1 : 0;

    $khusus_espk_main = isset($_POST['khusus_espk_main']) ? 1 : 0;
    $khusus_espk_ping = $khusus_espk_main ? 1 : 0;
    $khusus_espk_url  = $khusus_espk_main ? 1 : 0;
    $khusus_espk_func = $khusus_espk_main ? 1 : 0;

    $khusus_hrmis_main = isset($_POST['khusus_hrmis_main']) ? 1 : 0;
    $khusus_hrmis_func = $khusus_hrmis_main ? 1 : 0;

    // ----------------- Masukkan ke DB (satu rekod) -----------------
    $sql = "INSERT INTO penyelenggaraan 
        (nama_bahagian, nama_pengguna, tarikh, juruteknik, pegawai, sesi_tahunan,selenggara, bil_selenggara,
         jenis_kemudahan, model, no_siri, processor, ram, hdd, optical_drive, sistem_operasi, alamat_ip, antivirus,
         jenis_pencetak, ups, aksesori,
         cpu_clean, cpu_power_supply, cpu_ram_sub, cpu_processor_sub, cpu_motherboard, cpu_port,
         kb_clean, kb_test_keys, kb_capslock, kb_numlock,
         mouse_click, mouse_scroll, mouse_clean,
         hdd_partition_chk, hdd_partition_val, hdd_cleanup, hdd_scandisk, hdd_defrag, hdd_totalspace_chk, hdd_totalspace_val,
         monitor_clean, monitor_led_chk, monitor_led_val, monitor_test_chk, monitor_line_val, monitor_pixel_val,
         printer_clean, printer_led_chk, printer_led_val, printer_maintenance_chk, printer_roller, printer_head_clean, printer_head_align, printer_test,
         scanner_chk, scanner_align, scanner_test,
         ping_21, status_21, ping_22, status_22, ping_140, status_140, ping_34, status_34, win_update, win_antivirus_update, win_antivirus_scan, win_driver_update, win_dir_repair, win_recyclebin, win_restart, win_bios_default,
browser_clear_history, browser_homepage, browser_plugin_update,
app_office_main, app_office_word, app_office_excel, app_office_powerpoint, app_antivirus_chk, app_antivirus_val, app_autocad, app_mapinfo,
app_adobe_main, app_adobe_acrobat, app_adobe_photoshop, app_winzip, app_firefox,
khusus_epbt_main, khusus_epbt_ping, khusus_epbt_func,
khusus_ehadir_main, khusus_ehadir_ping, khusus_ehadir_url, khusus_ehadir_func,
khusus_espk_main, khusus_espk_ping, khusus_espk_url, khusus_espk_func,
khusus_hrmis_main, khusus_hrmis_func

        ) VALUES (
        '$nama_bahagian','$nama_pengguna','$tarikh','$juruteknik','$pegawai','$sesi_tahunan','$selenggara','$bil_selenggara',
        '$jenis_kemudahan','$model','$no_siri','$processor','$ram','$hdd','$optical_drive','$os','$ip','$antivirus',
        '$jenis_pencetak','$ups','$aksesori',
        '$cpu_clean','$cpu_power_supply','$cpu_ram_sub','$cpu_processor_sub','$cpu_motherboard','$cpu_port',
        '$kb_clean','$kb_test_keys','$kb_capslock','$kb_numlock',
        '$mouse_click','$mouse_scroll','$mouse_clean',
        '$hdd_partition_chk','$hdd_partition_val','$hdd_cleanup','$hdd_scandisk','$hdd_defrag','$hdd_totalspace_chk','$hdd_totalspace_val',
        '$monitor_clean','$monitor_led_chk','$monitor_led_val','$monitor_test_chk','$monitor_line_val','$monitor_pixel_val',
        '$printer_clean','$printer_led_chk','$printer_led_val','$printer_maintenance_chk','$printer_roller','$printer_head_clean','$printer_head_align','$printer_test',
        '$scanner_chk','$scanner_align','$scanner_test',
        '$ping_21','$status_21','$ping_22','$status_22','$ping_140','$status_140','$ping_34','$status_34','$win_update','$win_antivirus_update','$win_antivirus_scan','$win_driver_update','$win_dir_repair','$win_recyclebin','$win_restart','$win_bios_default',
'$browser_clear_history','$browser_homepage','$browser_plugin_update',
'$app_office_main','$app_office_word','$app_office_excel','$app_office_powerpoint','$app_antivirus_chk','$app_antivirus_val','$app_autocad','$app_mapinfo',
'$app_adobe_main','$app_adobe_acrobat','$app_adobe_photoshop','$app_winzip','$app_firefox',
'$khusus_epbt_main','$khusus_epbt_ping','$khusus_epbt_func',
'$khusus_ehadir_main','$khusus_ehadir_ping','$khusus_ehadir_url','$khusus_ehadir_func',
'$khusus_espk_main','$khusus_espk_ping','$khusus_espk_url','$khusus_espk_func',
'$khusus_hrmis_main','$khusus_hrmis_func'

    )";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Maklumat berjaya disimpan');
                window.location.href = 'dashboard_a.php';
              </script>";
        exit();
    } else {
        echo "<div style='color:red'>DB Error: " . mysqli_error($conn) . "</div>";
    }

}
?>
<!doctype html>
<html lang="ms">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SISTEM PENYELENGGARAAN ICT</title>
  <link rel="icon" type="image/png" href="ictlogo.png" />  <link rel="stylesheet" href="style.css">

  <style>
body{
  margin: 0;
  padding: 0;
  min-height: 100vh;
  overflow-x: hidden;
  overflow-y: auto;
  background:
    radial-gradient(1200px 800px at 10% 10%, #0a1a33 0%, transparent 60%),
    radial-gradient(900px 700px at 90% 20%, #0b002b 0%, transparent 70%),
    linear-gradient(160deg, #030014, #001a37);
  color: #e6eeff;
  font-family: 'Poppins', sans-serif;
}

.grid-dots{
  position: fixed; inset: 0; pointer-events: none;
  z-index: -2;
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
</style>

<script>
function toggleAll(source, className) {
    const checkboxes = document.querySelectorAll('.' + className);
    checkboxes.forEach(cb => {
        cb.checked = source.checked;
        updateLabelHighlight(cb);
        toggleRelatedInput(cb);
    });
}

function updateLabelHighlight(checkbox) {
    const label = checkbox.closest('label');
    if(label) {
        label.classList.toggle('checked-label', checkbox.checked);
    }
}

function toggleRelatedInput(checkbox) {
    const nextElem = checkbox.closest('label')?.nextElementSibling;

    if(nextElem && (nextElem.tagName === 'SELECT' || nextElem.tagName === 'INPUT')) {
        nextElem.disabled = !checkbox.checked;
    }

    if(checkbox.name === 'monitor_test_chk') {
        const subItems = checkbox.closest('fieldset').querySelectorAll('.sub-items select');
        subItems.forEach(sel => sel.disabled = !checkbox.checked);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const allCheckboxes = document.querySelectorAll('input[type=checkbox]');
    allCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            updateLabelHighlight(cb);
            toggleRelatedInput(cb);
        });
        updateLabelHighlight(cb);
        toggleRelatedInput(cb);
    });

    const namaBahagian = document.getElementById('nama_bahagian');
    const namaPengguna = document.getElementById('nama_pengguna');

    namaBahagian.addEventListener('change', function() {
        const jabatan = this.value;
        namaPengguna.innerHTML = '<option value=\"\">-- Sila Tunggu... --</option>';

        if(jabatan !== '') {
            fetch('get_pengguna.php?jabatan=' + encodeURIComponent(jabatan))
            .then(response => response.json())
            .then(data => {
                namaPengguna.innerHTML = '<option value=\"\">-- Pilih Nama Pengguna --</option>';
                if(data.length === 0){
                    namaPengguna.innerHTML = '<option value=\"\">-- Tiada pengguna --</option>';
                    return;
                }
                data.forEach(user => {
                    const opt = document.createElement('option');
                    opt.value = user.nama_pengguna;
                    opt.textContent = user.nama_pengguna;
                    namaPengguna.appendChild(opt);
                });
            })
            .catch(err => {
                console.error(err);
                namaPengguna.innerHTML = '<option value=\"\">-- Gagal memuatkan --</option>';
            });
        } else {
            namaPengguna.innerHTML = '<option value=\"\">-- Pilih Nama Bahagian Terlebih Dahulu --</option>';
        }
    });
});
</script>

</head>
<body>

<div class="grid-dots"></div>

<!-- butang toggle -->
<button class="toggle-btn" onclick="document.querySelector('.sidebar').classList.toggle('collapsed')">☰</button>

<!-- sidebar mula tertutup -->
<aside class="sidebar collapsed">
  <div class="sidebar-header">MENU</div>
  <ul class="sidebar-menu">
    <!-- ✅ UBAH DI SINI SAHAJA: tambah onclick redirect paksa -->
    <li data-tooltip="Dashboard">
      <a href="dashboard_a.php" onclick="window.location.href='dashboard_a.php'; return false;">🏠</a>
    </li>
  </ul>
</aside>

<div class="content">
  <h2 class="futuristic-legend">SENARAI SEMAK PENYELENGGARAAN BERKALA</h2>

  <div class="form-wrapper">
    <form method="post" autocomplete="off">

      <div class="form-grid">
        <fieldset class="fieldset-a">
          <legend>A. MAKLUMAT PENGGUNA</legend>

<label>Nama Bahagian:
  <select name="nama_bahagian" id="nama_bahagian" required>
    <option value="">-- Pilih Jabatan --</option>
    <?php
    $res = mysqli_query($conn, "SELECT DISTINCT jabatan FROM pengguna_jabatan ORDER BY jabatan ASC");
    while($row = mysqli_fetch_assoc($res)) {
        echo '<option value="'.htmlspecialchars($row['jabatan']).'">'.htmlspecialchars($row['jabatan']).'</option>';
    }
    ?>
  </select>
</label>

<label>Nama Pengguna:
  <select name="nama_pengguna" id="nama_pengguna" required>
    <option value="">-- Pilih Nama Pengguna --</option>
  </select>
</label>

<label>Tarikh:
  <input type="date" name="tarikh" value="<?php echo $today; ?>">
</label>

<label>Pegawai Teknikal:
  <select name="juruteknik" required>
    <option value="Mohd Hafiz Bin Tajuddin" <?php echo (isset($juruteknik) && $juruteknik === 'Mohd Hafiz Bin Tajuddin') ? 'selected' : ''; ?>>Mohd Hafiz Bin Tajuddin</option>
    <option value="Zaki Bin Abdul Naim" <?php echo (isset($juruteknik) && $juruteknik === 'Zaki Bin Abdul Naim') ? 'selected' : ''; ?>>Zaki Bin Abdul Naim</option>
  </select>
</label>

<label>Pegawai Pemantau:
  <input type="text" value="Ts.Shahzan Hafiz Bin Zainal Abidin" disabled>
</label>
<label>Sesi Semakan Tahunan:
  <input type="text" name="sesi_tahunan">
</label>
<label> Bil. Selenggara:
  <input type="text" name="selenggara">
</label>
<label>Selenggara Kali Ke-:
  <select name="bil_selenggara" required>
    <option value="">-- Pilih  --</option>
    <option value="1">1</option>
    <option value="2">2</option>
  </select>
</label>

        </fieldset>

        <fieldset class="fieldset-b">
          <legend>B. MAKLUMAT KEMUDAHAN ICT</legend>
          <label>Jenis Kemudahan: <input type="text" name="jenis_kemudahan"></label>
          <label>Model: <input type="text" name="model"></label>
          <label>No. Siri Pendaftaran: <input type="text" name="no_siri"></label>
          <label>Jenis Processor: <input type="text" name="processor"></label>
          <label>Kapasiti RAM (Memory): <input type="text" name="ram"></label>
          <label>Kapasiti HDD: <input type="text" name="hdd"></label>
          <label>Jenis & Status Pemacu Cakera: <input type="text" name="optical_drive"></label>
          <label>Sistem Pengoperasi: <input type="text" name="os"></label>
          <label>Alamat IP: <input type="text" name="ip"></label>
          <label>Jenis & Status Antivirus: <input type="text" name="antivirus"></label>
        </fieldset>
      </div>

      <fieldset class="fieldset-c">
        <legend>C. MAKLUMAT PENCETAK DAN AKSESSORI</legend>
        <label>Jenis Pencetak: <input type="text" name="jenis_pencetak"></label>
        <label>Jenis & Status UPS/AVR: <input type="text" name="ups"></label>
        <label>Jenis Aksessori Lain: <input type="text" name="aksesori"></label>
      </fieldset>

      <!-- Perkakasan checklist -->
      <div class="form-grid">

        <fieldset class="fieldset-cpu">
          <legend>CPU Unit</legend>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'cpu-checkbox')"> Pilih Semua</label>
          <label><input type="checkbox" name="cpu_clean" class="cpu-checkbox"> Membersihkan habuk dalam CPU Unit</label>
          <div class="sub-items">
            <div>➤ Membersihkan Power Supply </div>
            <div>➤ RAM </div>
            <div>➤ Processor </div>
            <div>➤ Papan induk </div>
            <div>➤ Uji Port pada Papan Induk </div>
          </div>
        </fieldset>

        <fieldset class="fieldset-keyboard">
          <legend>Papan Kekunci</legend>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'keyboard-checkbox')"> Pilih Semua</label>
          <label><input type="checkbox" name="kb_clean" class="keyboard-checkbox"> Membersihkan keyboard</label>
          <label><input type="checkbox" name="kb_test_keys" class="keyboard-checkbox"> Memeriksa setiap butang supaya berfungsi</label>
          <label><input type="checkbox" name="kb_capslock" class="keyboard-checkbox"> Uji Butang Caps Lock</label>
          <label><input type="checkbox" name="kb_numlock" class="keyboard-checkbox"> Uji Butang Num Lock</label>
        </fieldset>

        <fieldset class="fieldset-mouse">
          <legend>Tetikus</legend>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'mouse-checkbox')"> Pilih Semua</label>
          <label><input type="checkbox" name="mouse_click" class="mouse-checkbox"> Menguji butang kiri dan kanan</label>
          <label><input type="checkbox" name="mouse_scroll" class="mouse-checkbox"> Menguji Scroll</label>
          <label><input type="checkbox" name="mouse_clean" class="mouse-checkbox"> Membersih Track Ball (Ball Mouse)</label>
        </fieldset>

        <fieldset class="fieldset-hdd">
          <legend>Cakera Keras</legend>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'hdd-checkbox')"> Pilih Semua</label>
          <label><input type="checkbox" name="hdd_partition_chk" class="hdd-checkbox"> Bilangan Partition :</label>
          <input type="text" name="hdd_partition_val" placeholder="cth: 2">
          <label><input type="checkbox" name="hdd_cleanup" class="hdd-checkbox"> Disk Cleanup</label>
          <label><input type="checkbox" name="hdd_scandisk" class="hdd-checkbox"> Scandisk</label>
          <label><input type="checkbox" name="hdd_defrag" class="hdd-checkbox"> Disk Defragmenter</label>
          <label><input type="checkbox" name="hdd_totalspace_chk" class="hdd-checkbox"> Total Space C :</label>
          <input type="text" name="hdd_totalspace_val" placeholder="cth: 120/500">
        </fieldset>

        <fieldset class="fieldset-monitor">
          <legend>Skrin Paparan (Monitor)</legend>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'monitor-checkbox')"> Pilih Semua</label>
          <label><input type="checkbox" name="monitor_clean" class="monitor-checkbox"> Membersihkan Skrin</label>
          <label><input type="checkbox" name="monitor_led_chk" class="monitor-checkbox"> Status warna lampu LED :</label>
          <select name="monitor_led_val">
            <option value="">-- Pilih --</option>
            <option value="Hijau">Hijau</option>
            <option value="Biru">Biru</option>
            <option value="Orange">Orange</option>
            <option value="Merah">Merah</option>
          </select>
          <label><input type="checkbox" name="monitor_test_chk" class="monitor-checkbox"> Uji skrin monitor</label>
          <div class="sub-items">
            <div>➤ Garisan pada skrin:
              <select name="monitor_line_val"><option value="">-- Pilih --</option><option value="Ada">Ada</option><option value="Tiada">Tiada</option></select>
            </div>
            <div>➤ Titik Pixel mati:
              <select name="monitor_pixel_val"><option value="">-- Pilih --</option><option value="Ada">Ada</option><option value="Tiada">Tiada</option></select>
            </div>
          </div>
        </fieldset>

      </div>

      <div class="printer-network-wrapper">
        <fieldset class="fieldset-printer-scanner">
          <h4>Pencetak</h4>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'printer-checkbox')"> Pilih Semua</label>
          <label><input type="checkbox" name="printer_clean" class="printer-checkbox"> Membersihkan pencetak</label>
          <label><input type="checkbox" name="printer_led_chk" class="printer-checkbox"> Status warna lampu LED :</label>
          <select name="printer_led_val"><option value="">-- Pilih --</option><option value="Hijau">Hijau</option><option value="Orange">Orange</option><option value="Merah">Merah</option></select>

          <label><input type="checkbox" name="printer_maintenance_chk" class="printer-checkbox"> Melaksanakan Printer software maintenance</label>
          <div class="maintenance-sub">
            <div>➤ Clean Roller </div>
            <div>➤ Printhead Cleaning</div>
            <div>➤ Printhead Alignment </div>
            <div>➤ Print Test </div>
          </div>

          <label><input type="checkbox" name="scanner_chk" class="printer-checkbox"> Pengimbas (Scanner) – untuk AIO</label>
          <div class="maintenance-sub">
            <div>➤ Scan Alignment </div>
            <div>➤ Scan Test </div>
          </div>
        </fieldset>

        <fieldset class="fieldset-network">
          <h4>Rangkaian (Network)</h4>
          <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'network-checkbox')"> Pilih Semua</label>

          <label><input type="checkbox" name="ping_21" class="network-checkbox"> a. 10.112.10.21</label>
          <select name="status_21">
            <option value="">-- Pilih Status --</option>
            <option value="OK">OK</option>
            <option value="Limited Connectivity">Limited Connectivity</option>
            <option value="Limited Access">Limited Access</option>
            <option value="No Connection">No Connection</option>
          </select>

          <label><input type="checkbox" name="ping_22" class="network-checkbox"> b. 10.112.10.22</label>
          <select name="status_22">
            <option value="">-- Pilih Status --</option>
            <option value="OK">OK</option>
            <option value="Limited Connectivity">Limited Connectivity</option>
            <option value="Limited Access">Limited Access</option>
            <option value="No Connection">No Connection</option>
          </select>

          <label><input type="checkbox" name="ping_140" class="network-checkbox"> c. 10.112.10.140</label>
          <select name="status_140">
            <option value="">-- Pilih Status --</option>
            <option value="OK">OK</option>
            <option value="Limited Connectivity">Limited Connectivity</option>
            <option value="Limited Access">Limited Access</option>
            <option value="No Connection">No Connection</option>
          </select>

          <label><input type="checkbox" name="ping_34" class="network-checkbox"> d. 10.112.10.34</label>
          <select name="status_34">
            <option value="">-- Pilih Status --</option>
            <option value="OK">OK</option>
            <option value="Limited Connectivity">Limited Connectivity</option>
            <option value="Limited Access">Limited Access</option>
            <option value="No Connection">No Connection</option>
          </select>
        </fieldset>

        <div class="form-grid">
          <fieldset class="fieldset-windows">
            <legend>Sistem Pengoperasi (Windows)</legend>
            <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'win-checkbox')"> Pilih Semua</label>
            <label><input type="checkbox" name="win_update" class="win-checkbox"> Mengemaskini Windows</label>
            <label><input type="checkbox" name="win_antivirus_update" class="win-checkbox"> Mengemaskini Antivirus</label>
            <label><input type="checkbox" name="win_antivirus_scan" class="win-checkbox"> Menjalankan imbasan Antivirus</label>
            <label><input type="checkbox" name="win_driver_update" class="win-checkbox"> Mengemaskini Drivers (jika perlu)</label>
            <label><input type="checkbox" name="win_dir_repair" class="win-checkbox"> Windows Directory File Repair</label>
            <label><input type="checkbox" name="win_recyclebin" class="win-checkbox"> Mengosongkan Recycle Bin</label>
            <label><input type="checkbox" name="win_restart" class="win-checkbox"> Restart</label>
            <label><input type="checkbox" name="win_bios_default" class="win-checkbox"> Default Bios Setting (jika perlu)</label>
          </fieldset>

          <fieldset class="fieldset-browser">
            <legend>Pelayar Web</legend>
            <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'browser-checkbox')"> Pilih Semua</label>
            <label><input type="checkbox" name="browser_clear_history" class="browser-checkbox"> Clear recent history</label>
            <label><input type="checkbox" name="browser_homepage" class="browser-checkbox"> Set Homepage:
              <a href="http://www.mdtm.gov.my" target="_blank">www.mdtm.gov.my</a>
            </label>
            <label><input type="checkbox" name="browser_plugin_update" class="browser-checkbox"> Kemaskini Plugin software</label>
          </fieldset>

          <fieldset class="fieldset-app">
            <legend>Sistem Aplikasi Umum</legend>
            <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'app-checkbox')"> Pilih Semua</label>
            <label><input type="checkbox" name="app_office_main" class="app-checkbox"> Microsoft Office</label>
            <div class="sub-items">➤ Word<br>➤ Excel<br>➤ Powerpoint</div>
            <label><input type="checkbox" name="app_antivirus_chk" class="app-checkbox"> Antivirus</label>
            <input type="text" name="app_antivirus_val" placeholder="Jenis Antivirus">
            <label><input type="checkbox" name="app_autocad" class="app-checkbox"> Autocad</label>
            <label><input type="checkbox" name="app_mapinfo" class="app-checkbox"> Mapinfo</label>
            <label><input type="checkbox" name="app_adobe_main" class="app-checkbox"> Adobe</label>
            <div class="sub-items">➤ Acrobat<br>➤ Photoshop</div>
            <label><input type="checkbox" name="app_winzip" class="app-checkbox"> Winzip</label>
            <label><input type="checkbox" name="app_firefox" class="app-checkbox"> Mozilla Firefox</label>
          </fieldset>

          <fieldset class="fieldset-khusus">
            <legend>Sistem Aplikasi Khusus</legend>
            <label class="main-label"><input type="checkbox" onclick="toggleAll(this,'khusus-checkbox')"> Pilih Semua</label>

            <label><input type="checkbox" name="khusus_epbt_main" class="khusus-checkbox"> Sistem e-PBT</label>
            <div class="sub-items">➤ Ping 10.112.10.34<br>➤ Boleh Berfungsi</div>

            <label><input type="checkbox" name="khusus_ehadir_main" class="khusus-checkbox"> Sistem e-Hadir</label>
            <div class="sub-items">➤ Ping 10.112.10.140<br>➤ Uji ip pada url address web browser<br>➤ Boleh Berfungsi</div>

            <label><input type="checkbox" name="khusus_espk_main" class="khusus-checkbox"> Sistem eSPK (Jab. Penguatkuasaan)</label>
            <div class="sub-items">➤ Ping 10.112.10.7<br>➤ Uji ip pada url address web browser<br>➤ Boleh Berfungsi</div>

            <label><input type="checkbox" name="khusus_hrmis_main" class="khusus-checkbox"> Sistem HrMIS</label>
            <div class="sub-items">➤ Boleh Berfungsi</div>
          </fieldset>

        </div>
      </div>

      <br>
      <input type="submit" value="Hantar" class="btn-futuristic">
    </form>

    <?php include 'footer.php'; ?>
  </div>
</div>

</body>
</html>
