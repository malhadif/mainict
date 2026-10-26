<?php
include 'auth.php';
include 'db.php';

// Pastikan ada ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Padam rekod
    $delete = mysqli_query($conn, "DELETE FROM penyelenggaraan WHERE id = '$id'");

    if ($delete) {
        echo "<script>alert('Rekod berjaya dipadam.'); window.location.href='dashboard_a.php';</script>";
    } else {
        echo "<script>alert('Ralat: Tidak dapat memadam rekod.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ID tidak sah.'); window.history.back();</script>";
}
?>
