<?php
include 'auth.php';
include 'db.php';

// Ambil data dari borang dan tukar ke huruf besar
$jabatan = strtoupper(trim($_POST['jabatan']));
$nama_pengguna = strtoupper(trim($_POST['nama_pengguna']));

// Semak data tak kosong
if (!empty($jabatan) && !empty($nama_pengguna)) {
    $sql = "INSERT INTO pengguna_jabatan (jabatan, nama_pengguna) VALUES ('$jabatan', '$nama_pengguna')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: daftar_pengguna.php?success=1");
        exit();
    } else {
        die("Ralat SQL: " . mysqli_error($conn));
    }
} else {
    echo "<script>alert('Sila isi semua maklumat.'); window.location.href='daftar_pengguna.php';</script>";
}
?>
