<?php
include 'db.php';

$sql = "SELECT id, password FROM pengguna";
$result = mysqli_query($conn, $sql);

while ($user = mysqli_fetch_assoc($result)) {
    if (strlen($user['password']) < 60) {
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE pengguna SET password='$hashed_password' WHERE id=" . $user['id']);
    }
}

echo "Semua password lama telah di-hash!";
