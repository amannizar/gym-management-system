<?php
require_once "backend/db.php";

$sql = "CREATE TABLE IF NOT EXISTS member_health (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    diabetes ENUM('Yes', 'No') DEFAULT 'No',
    heart_condition ENUM('Yes', 'No') DEFAULT 'No',
    asthma ENUM('Yes', 'No') DEFAULT 'No',
    injuries TEXT,
    other_info TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'member_health' created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

echo "<a href='index.php'>Back to Home</a>";
?>
