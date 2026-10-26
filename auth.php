<?php
session_start();
include 'db.php';

// Kalau belum login
if (!isset($_SESSION['username']) || !isset($_SESSION['session_id'])) {
    header("Location: login.php");
    exit();
}

// Semak session ID di DB
$username = mysqli_real_escape_string($conn, $_SESSION['username']);
$sql = "SELECT session_id FROM pengguna WHERE TRIM(username)='$username' LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Kalau session ID tak sama → logout
if (!$row || $row['session_id'] !== $_SESSION['session_id']) {
    session_destroy();
    header("Location: login.php?msg=Sesi anda telah tamat kerana log masuk di tempat lain");
    exit();
}
?>

<!-- Sekatan Tab DIBUANG supaya sistem boleh buka banyak tab -->
