<?php
require_once __DIR__ . "/backend/db.php";

echo "<h2>Debug Auth</h2>";

// 1. Check if user exists
$result = mysqli_query($conn, "SELECT * FROM admins WHERE username='admin'");
if ($row = mysqli_fetch_assoc($result)) {
    echo "User 'admin' found.<br>";
    echo "Stored Hash: " . $row['password'] . "<br>";
    
    // 2. Test Password Verify
    if (password_verify('admin123', $row['password'])) {
        echo "<span style='color:green'>PASSWORD VERIFY: SUCCESS</span><br>";
    } else {
        echo "<span style='color:red'>PASSWORD VERIFY: FAILED</span><br>";
        
        // Fix it
        echo "Attempting to reset password...<br>";
        $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE admins SET password='$new_hash' WHERE username='admin'");
        if ($update) {
            echo "Password has been reset to 'admin123'. Try logging in again.<br>";
        }
    }
} else {
    echo "User 'admin' NOT FOUND.<br>";
    // Create it
    $pass = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('admin', '$pass')");
    echo "Created user 'admin' with password 'admin123'.";
}
?>
