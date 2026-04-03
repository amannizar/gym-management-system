<?php
require_once "backend/db.php";

$sql_updates = [
    // 1. Health Metrics Extended
    "ALTER TABLE health_metrics ADD COLUMN target_calories INT DEFAULT 2000",
    "ALTER TABLE health_metrics ADD COLUMN target_protein INT DEFAULT 100",
    "ALTER TABLE health_metrics ADD COLUMN target_water INT DEFAULT 3000",
    "ALTER TABLE health_metrics ADD COLUMN target_sleep DECIMAL(4,1) DEFAULT 8.0",
    "ALTER TABLE health_metrics ADD COLUMN daily_water_log INT DEFAULT 0",
    "ALTER TABLE health_metrics ADD COLUMN last_sleep_data TEXT NULL",

    // 2. Diet Consultations Table
    "CREATE TABLE IF NOT EXISTS diet_consults (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        user_notes TEXT,
        coach_response TEXT,
        status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
    )",

    // 3. Premium Plans Table
    "CREATE TABLE IF NOT EXISTS premium_plans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        plan_type VARCHAR(50) DEFAULT 'Monthly Diet',
        status ENUM('Active', 'Expired') DEFAULT 'Active',
        start_date DATE,
        end_date DATE,
        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
    )"
];

foreach ($sql_updates as $sql) {
    try {
        mysqli_query($conn, $sql);
        echo "<p>Executed: " . htmlspecialchars(substr($sql, 0, 50)) . "... <br> Status: " . (mysqli_error($conn) ? mysqli_error($conn) : "Success ✅") . "</p>";
    } catch (Exception $e) {
        echo "<p>Skipped (likely exists)</p>";
    }
}
?>
