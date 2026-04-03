<?php
session_start();

$redirect = "login.php"; // Default to Admin/Main login
if (isset($_SESSION['member_id'])) {
    $redirect = "member_login.php";
}

session_destroy();
header("Location: $redirect");
exit;
?>
