<?php
include 'db.php';

// dapatkan ID rekod
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = mysqli_query($conn, "SELECT * FROM penyelenggaraan WHERE id = $id LIMIT 1");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Rekod tidak ditemui.");
}

// fungsi tick/pangkah
function tick($val) {
    return $val == 1 
        ? "<span style='color:green;font-weight:bold;'>✔</span>" 
        : "<span style='color:red;font-weight:bold;'>❌</span>";
}
?>
<!doctype html>
<html lang="ms">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cetak Borang Penyelenggaraan ICT</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: white;
    color: #000;
    margin: 40px;
}
h2 {
    text-align: center;
    text-transform: uppercase;
    font-size: 20px;
    margin-bottom: 25px;
}
fieldset {
    margin-bottom: 15px;
    border: 1px solid #000;
    border-radius: 6px;
    padding: 10px 15px;
}
legend {
    font-weight: bold;
    text-transform: uppercase;
    padding: 0 10px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
}
td {
    padding: 6px 8px;
    vertical-align: top;
    border-bottom: 1px solid #ddd;
}
td:first-child {
    width: 40%;
    font-weight: bold;
}
.print-btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 18px;
    background: #004aad;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.print-btn:hover {
    background: #003080;
}
@media print {
    .print-btn { display: none; }
    body { margin: 10px; }
}
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨 Cetak Borang</button>

<h2>SENARAI SEMAK PENYELENGGARAAN BERKALA</h2>

<fieldset>
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

<fieldset>
<legend>B. Maklumat Kemudahan ICT</legend>\
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

<fieldset>
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

<fieldset>
<legend>Papan Kekunci</legend>
<table>
<tr><td>Membersihkan Keyboard</td><td><?= tick($data['kb_clean']); ?></td></tr>
<tr><td>Uji Setiap Butang</td><td><?= tick($data['kb_test_keys']); ?></td></tr>
<tr><td>Uji Caps Lock</td><td><?= tick($data['kb_capslock']); ?></td></tr>
<tr><td>Uji Num Lock</td><td><?= tick($data['kb_numlock']); ?></td></tr>
</table>
</fieldset>

<fieldset>
<legend>Tetikus</legend>
<table>
<tr><td>Uji Butang Klik</td><td><?= tick($data['mouse_click']); ?></td></tr>
<tr><td>Uji Scroll</td><td><?= tick($data['mouse_scroll']); ?></td></tr>
<tr><td>Membersih Track Ball</td><td><?= tick($data['mouse_clean']); ?></td></tr>
</table>
</fieldset>

<fieldset>
<legend>Cakera Keras (HDD)</legend>
<table>
<tr><td>Bilangan Partition</td><td><?= tick($data['hdd_partition_chk']); ?> <?= htmlspecialchars($data['hdd_partition_val']); ?></td></tr>
<tr><td>Disk Cleanup</td><td><?= tick($data['hdd_cleanup']); ?></td></tr>
<tr><td>Scandisk</td><td><?= tick($data['hdd_scandisk']); ?></td></tr>
<tr><td>Disk Defragmenter</td><td><?= tick($data['hdd_defrag']); ?></td></tr>
<tr><td>Total Space C:</td><td><?= tick($data['hdd_totalspace_chk']); ?> <?= htmlspecialchars($data['hdd_totalspace_val']); ?></td></tr>
</table>
</fieldset>

<fieldset>
<legend>Skrin Paparan (Monitor)</legend>
<table>
<tr><td>Membersihkan Skrin</td><td><?= tick($data['monitor_clean']); ?></td></tr>
<tr><td>Status LED</td><td><?= htmlspecialchars($data['monitor_led_val']); ?></td></tr>
<tr><td>Uji Skrin Monitor</td><td><?= tick($data['monitor_test_chk']); ?></td></tr>
<tr><td>Garisan pada Skrin</td><td><?= htmlspecialchars($data['monitor_line_val']); ?></td></tr>
<tr><td>Titik Pixel Mati</td><td><?= htmlspecialchars($data['monitor_pixel_val']); ?></td></tr>
</table>
</fieldset>

<fieldset>
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

<fieldset>
<legend>Rangkaian (Network)</legend>
<table>
<tr><td>10.112.10.21</td><td><?= tick($data['ping_21']); ?> Status: <?= htmlspecialchars($data['status_21']); ?></td></tr>
<tr><td>10.112.10.22</td><td><?= tick($data['ping_22']); ?> Status: <?= htmlspecialchars($data['status_22']); ?></td></tr>
<tr><td>10.112.10.140</td><td><?= tick($data['ping_140']); ?> Status: <?= htmlspecialchars($data['status_140']); ?></td></tr>
<tr><td>10.112.10.34</td><td><?= tick($data['ping_34']); ?> Status: <?= htmlspecialchars($data['status_34']); ?></td></tr>
</table>
</fieldset>

<fieldset>
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

<fieldset>
<legend>Pelayar Web</legend>
<table>
<tr><td>Clear History</td><td><?= tick($data['browser_clear_history']); ?></td></tr>
<tr><td>Homepage (www.mdtm.gov.my)</td><td><?= tick($data['browser_homepage']); ?></td></tr>
<tr><td>Plugin Update</td><td><?= tick($data['browser_plugin_update']); ?></td></tr>
</table>
</fieldset>

<fieldset>
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

<fieldset>
<legend>Sistem Aplikasi Khusus</legend>
<table>
<tr><td>e-PBT</td><td><?= tick($data['khusus_epbt_main']); ?> Ping: <?= tick($data['khusus_epbt_ping']); ?> Fungsi: <?= tick($data['khusus_epbt_func']); ?></td></tr>
<tr><td>e-Hadir</td><td><?= tick($data['khusus_ehadir_main']); ?> Ping: <?= tick($data['khusus_ehadir_ping']); ?> Uji URL: <?= tick($data['khusus_ehadir_url']); ?> Fungsi: <?= tick($data['khusus_ehadir_func']); ?></td></tr>
<tr><td>eSPK (Penguatkuasaan)</td><td><?= tick($data['khusus_espk_main']); ?> Ping: <?= tick($data['khusus_espk_ping']); ?> Uji URL: <?= tick($data['khusus_espk_url']); ?> Fungsi: <?= tick($data['khusus_espk_func']); ?></td></tr>
<tr><td>HrMIS</td><td><?= tick($data['khusus_hrmis_main']); ?> Fungsi: <?= tick($data['khusus_hrmis_func']); ?></td></tr>
</table>
</fieldset>

<?php if (isset($data['status']) && strtolower($data['status']) == 'disahkan'): ?>
    <div style="margin-top:40px; text-align:center; font-weight:bold; font-size:16px;">
        Borang ini telah disahkan oleh Ts. Shahzan Hafiz Bin Zainal Abidin.
    </div>
<?php endif; ?>

</body>
</html>
