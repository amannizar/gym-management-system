<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "backend/db.php";

// Auth check (since we haven't included member_nav yet)
if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit;
}

$member_id = $_SESSION['member_id'];

// Handle Water Add
if (isset($_POST['add_water'])) {
    mysqli_query($conn, "UPDATE health_metrics SET daily_water_log = daily_water_log + 250 WHERE member_id=$member_id");
    header("Location: water_tracker.php");
    exit;
}
if (isset($_POST['reset_water'])) {
    mysqli_query($conn, "UPDATE health_metrics SET daily_water_log = 0 WHERE member_id=$member_id");
    header("Location: water_tracker.php");
    exit;
}

// Fetch Data
$health = mysqli_fetch_assoc(mysqli_query($conn, "SELECT daily_water_log, target_water FROM health_metrics WHERE member_id=$member_id"));
$current = $health['daily_water_log'] ?? 0;
$target = $health['target_water'] ?? 3000;
$percent = min(100, round(($current / $target) * 100));

// Include Nav (Outputs HTML) - MOVED TO BODY
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Water Tracker 💧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .water-tank {
            width: 150px;
            height: 250px;
            border: 4px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
            background: rgba(0,0,0,0.3);
        }
        .water-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #3498db;
            transition: height 0.5s ease-in-out;
            opacity: 0.8;
        }
        .water-btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #3498db;
            color: white;
            border: none;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
            box-shadow: 0 0 20px rgba(52, 152, 219, 0.4);
            transition: 0.2s;
        }
        .water-btn:hover { transform: scale(1.1); }
    </style>
</head>
<body>
<?php require_once "backend/member_nav.php"; ?>
<div class="page d-block">
    <div class="container py-4 text-center">
        
        <h2 class="mb-4 text-info">Hydration Tracker 💧</h2>
        
        <div class="mb-3 text-start">
            <a href="member_dashboard.php" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4">
                    
                    <div class="water-tank">
                        <div class="water-fill" style="height: <?= $percent ?>%;"></div>
                        <div style="position: absolute; width: 100%; top: 50%; transform: translateY(-50%); font-weight: bold; text-shadow: 0 2px 4px black; color: white; font-size: 1.5rem;">
                            <?= $percent ?>%
                        </div>
                    </div>

                    <h3 class="mt-3 text-white"><?= $current ?> / <?= $target ?> ml</h3>

                    <form method="POST">
                        <button name="add_water" class="water-btn">+</button>
                        <p class="text-white-50">Add 250ml Glass</p>
                        
                        <button name="reset_water" class="btn btn-sm btn-outline-danger mt-3">Reset Day</button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
