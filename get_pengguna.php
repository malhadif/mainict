<?php
// get_pengguna.php
include 'db.php'; // sambungkan ke database

header('Content-Type: application/json');

if(isset($_GET['jabatan']) && $_GET['jabatan'] !== '') {
    $jabatan = mysqli_real_escape_string($conn, $_GET['jabatan']);
    
    $sql = "SELECT nama_pengguna FROM pengguna_jabatan WHERE jabatan='$jabatan' ORDER BY nama_pengguna ASC";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row; // akan mengandungi ['nama_pengguna' => 'Nama']
    }

    echo json_encode($data);
} else {
    echo json_encode([]);
}
?>
