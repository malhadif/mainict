<?php
session_start();

// Kalau belum log masuk, hantar balik ke halaman login
if (!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit;
}

// Guna 'level' (peranan) untuk tentukan dashboard, bukan username keras (hard-code)
if ($_SESSION['level'] === 'adminA') {
    header("Location: dashboard_a.php");
    exit;
} elseif ($_SESSION['level'] === 'adminB') {
    header("Location: dashboard_b.php");
    exit;
} else {
    echo "Pengguna tidak dikenali.";
}
?>
