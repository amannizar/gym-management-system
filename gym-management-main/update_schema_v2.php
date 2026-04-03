<?php
require_once __DIR__ . "/backend/db.php";

echo "<h2>Database Update v2</h2>";

// 1. Add password to members if not exists
$check = mysqli_query($conn, "SHOW COLUMNS FROM members LIKE 'password'");
if (mysqli_num_rows($check) == 0) {
    $sql = "ALTER TABLE members ADD COLUMN password VARCHAR(255) DEFAULT NULL";
    if (mysqli_query($conn, $sql)) {
        echo "Added 'password' column to members table.<br>";
        
        // Update existing members with default password 'member123'
        $default_pass = password_hash("member123", PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE members SET password='$default_pass' WHERE password IS NULL");
        echo "Updated existing members with default password 'member123'.<br>";
    } else {
        echo "Error altering members table: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "'password' column already exists in members table.<br>";
}

// 2. Create Workouts Table
$sql = "CREATE TABLE IF NOT EXISTS workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    date DATE NOT NULL,
    exercise VARCHAR(100) NOT NULL,
    sets INT NOT NULL,
    reps INT NOT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) echo "Table 'workouts' ready.<br>";

// 3. Create Diets Table
$sql = "CREATE TABLE IF NOT EXISTS diets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    description TEXT,
    calories INT NOT NULL,
    assigned_date DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) echo "Table 'diets' ready.<br>";

// 4. Create IoT Devices Table
$sql = "CREATE TABLE IF NOT EXISTS iot_devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    device_name VARCHAR(100) NOT NULL,
    status ENUM('Connected', 'Disconnected', 'Syncing') DEFAULT 'Disconnected',
    last_sync TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) echo "Table 'iot_devices' ready.<br>";

echo "<br>Done. <a href='index.php'>Go Home</a>";
?>
