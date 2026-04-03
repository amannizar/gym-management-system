<?php
require_once "backend/db.php";

// Disable foreign key checks to allow truncation
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

$tables = ['members', 'health_metrics', 'workouts'];
$messages = [];

foreach ($tables as $table) {
    if (mysqli_query($conn, "TRUNCATE TABLE $table")) {
        $messages[] = "Cleared table: <strong>$table</strong> ✅";
    } else {
        $messages[] = "Error clearing $table: " . mysqli_error($conn) . " ❌";
    }
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Reset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <body class="bg-dark text-white text-center py-5">
        <h1>System Reset Complete 🧹</h1>
        <div class="my-4">
            <?php foreach($messages as $msg) echo "<p>$msg</p>"; ?>
        </div>
        <a href="index.php" class="btn btn-primary">Start New Registration</a>
    </body>
</head>
</html>
