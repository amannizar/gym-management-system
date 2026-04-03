<?php
require_once "backend/db.php";

$pass = password_hash("admin123", PASSWORD_DEFAULT);
$sql = "UPDATE admins SET password='$pass' WHERE username='admin'";

if (mysqli_query($conn, $sql)) {
    echo "Admin password reset to: admin123";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
