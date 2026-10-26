<?php
$conn = mysqli_connect("localhost", "root", "", "main_ict");
if (!$conn) {
    die("Gagal sambung ke pangkalan data: " . mysqli_connect_error());
}
?>