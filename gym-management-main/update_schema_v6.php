<?php
require_once "backend/db.php";

$sql_adds = [
    "ADD COLUMN image_path VARCHAR(255) NULL"
];

foreach ($sql_adds as $add) {
    try {
        $sql = "ALTER TABLE workouts $add";
        mysqli_query($conn, $sql);
        echo "<p>Tried: $add <br> Result: " . (mysqli_error($conn) ? mysqli_error($conn) : "Success ✅") . "</p>";
    } catch (Exception $e) {
        echo "Column likely exists.";
    }
}
?>
