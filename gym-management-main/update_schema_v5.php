<?php
require_once "backend/db.php";

$sql_adds = [
    "ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
    "ADD COLUMN gender ENUM('Male', 'Female', 'Other') DEFAULT 'Male'",
    "ADD COLUMN dob DATE NULL",
    "ADD COLUMN blood_group VARCHAR(5) NULL",
    "ADD COLUMN address TEXT NULL",
    "ADD COLUMN emergency_contact VARCHAR(20) NULL"
];

foreach ($sql_adds as $add) {
    try {
        $sql = "ALTER TABLE members $add";
        mysqli_query($conn, $sql);
        echo "<p>Tried: $add <br> Result: " . (mysqli_error($conn) ? mysqli_error($conn) : "Success ✅") . "</p>";
    } catch (Exception $e) {
        echo "Column likely exists.";
    }
}
?>
