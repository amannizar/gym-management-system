<?php
require_once "backend/db.php";

$email = "anze@gmail.com"; // Resetting for user's main email
$new_pass = "1234";

// Assuming plain text based on previous profile.php context, OR checking auth_member.php next.
// If auth_member uses password_verify, I should hash it.
// Let's assume hash for safety as DB showed hashes.
$hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

$sql = "UPDATE members SET password='$hashed_pass' WHERE email='$email'";

if (mysqli_query($conn, $sql)) {
    echo "Password for $email reset to: $new_pass";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
