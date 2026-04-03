<?php
require_once __DIR__ . "/backend/db.php";

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'admins' created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

// Check if admin exists
$check = mysqli_query($conn, "SELECT * FROM admins WHERE username='admin'");
if (mysqli_num_rows($check) == 0) {
    // Create default admin (password: admin123)
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    $sql = "INSERT INTO admins (username, password) VALUES ('admin', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Default admin user created (User: admin, Pass: admin123).<br>";
    } else {
        echo "Error creating admin: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Admin user already exists.<br>";
}

echo "<br><a href='login.php'>Go to Login</a>";
?>
