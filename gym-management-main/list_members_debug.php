<?php
require_once "backend/db.php";

$sql = "DESCRIBE members";
$result = mysqli_query($conn, $sql);

echo "--- COLUMNS ---\n";
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n--- DATA ---\n";
$sql = "SELECT * FROM members LIMIT 5";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    print_r($row);
}
?>
