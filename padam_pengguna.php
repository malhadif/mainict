<?php
include 'db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $delete = mysqli_query($conn, "DELETE FROM pengguna_jabatan WHERE id = '$id'");

    if ($delete) {
        header("Location: senarai_pengguna.php?deleted=1");
    } else {
        header("Location: senarai_pengguna.php?deleted=0");
    }
    exit;
}
?>
