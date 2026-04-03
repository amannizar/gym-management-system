<?php
require_once "backend/db.php";

$sql_updates = [
    // Table to store generated weekly plans
    "CREATE TABLE IF NOT EXISTS member_plans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        member_id INT NOT NULL,
        plan_content TEXT NOT NULL,
        generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
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
