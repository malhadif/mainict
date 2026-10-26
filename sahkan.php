<?php include 'auth.php'; include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "UPDATE penyelenggaraan SET status='Disahkan oleh Admin B' WHERE id=$id");
}
header("Location: dashboard_b.php");
?>
