<?php
session_start();
include 'db.php';

if (isset($_SESSION['username'])) {
    mysqli_query($conn, "UPDATE pengguna SET session_id=NULL WHERE username='" . $_SESSION['username'] . "'");
}

session_destroy();
?>
<script>
// Padam lock tab untuk tab ini sahaja
sessionStorage.removeItem("ict_mdtm_active_tab");
window.location.href = "login.php";
</script>
