<?php
require_once "backend/db.php";

$username = "admin";
$new_pass = "admin123";
// Force a known simple hash if password_hash is suspected (it shouldn't be, but let's stick to standard)
$hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

$sql = "UPDATE admins SET password='$hashed_pass' WHERE username='$username'";

if (mysqli_query($conn, $sql)) {
    echo "<h1>Password Reset Successful</h1>";
    echo "<p>User: <strong>$username</strong></p>";
    echo "<p>Pass: <strong>$new_pass</strong></p>";
    echo "<p>Hash: " . substr($hashed_pass, 0, 10) . "...</p>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
