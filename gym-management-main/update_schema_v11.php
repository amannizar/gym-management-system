<?php
require_once "backend/db.php";

$sql = "ALTER TABLE workouts ADD COLUMN calories INT DEFAULT 0 AFTER reps;";

if (mysqli_query($conn, $sql)) {
    echo "Column 'calories' added to 'workouts' successfully.\n";
} else {
    echo "Error modifying table: " . mysqli_error($conn) . "\n";
}
?>
