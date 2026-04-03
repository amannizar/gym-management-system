<?php
require_once __DIR__ . "/backend/db.php";

echo "<h2>Database Update v3</h2>";

// Create Health Metrics Table
$sql = "CREATE TABLE IF NOT EXISTS health_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    age INT,
    height_cm INT,
    weight_kg DECIMAL(5,2),
    goal_weight_kg DECIMAL(5,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'health_metrics' ready.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

echo "<br>Done. <a href='member_dashboard.php'>Go to Dashboard</a>";
?>
