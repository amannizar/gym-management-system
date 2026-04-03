<?php
require_once "backend/member_nav.php";
require_once "backend/db.php";

$member_id = $_SESSION['member_id'];

// Handle New Log
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['exercise'])) {
    $date = date('Y-m-d');
    $ex = $_POST['exercise'];
    $sets = $_POST['sets'];
    $reps = $_POST['reps'];
    $calories = $_POST['calories'] ?? 0;
    
    $stmt = $conn->prepare("INSERT INTO workouts (member_id, date, exercise, sets, reps, calories) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiii", $member_id, $date, $ex, $sets, $reps, $calories);
    $stmt->execute();
}
// Fetch History
$result = mysqli_query($conn, "SELECT * FROM workouts WHERE member_id=$member_id ORDER BY date DESC LIMIT 10");

// Fetch Total Calories Today
$today = date('Y-m-d');
$cal_result = mysqli_query($conn, "SELECT SUM(calories) as total_cal FROM workouts WHERE member_id=$member_id AND date='$today'");
$cal_row = mysqli_fetch_assoc($cal_result);
$total_calories_today = $cal_row['total_cal'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workout Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .summary-card-cal {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(255, 75, 43, 0.4);
        }
        .summary-card-cal h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<div class="page d-block">
    <div class="container py-4">
        
        <div class="row align-items-stretch mb-4">
            <div class="col-md-12">
                <div class="card summary-card-cal p-4 text-center">
                    <h5>🔥 Total Calories Burned Today</h5>
                    <h3><?= $total_calories_today ?> kcal</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Log Set</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Exercise</label>
                                <select name="exercise" id="exercise" class="form-select text-white" onchange="calcCals()">
                                    <option>Bench Press</option>
                                    <option>Squat</option>
                                    <option>Deadlift</option>
                                    <option>Overhead Press</option>
                                    <option>Run (Treadmill)</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label>Sets</label>
                                    <input type="number" name="sets" id="sets" class="form-control text-white" value="3" oninput="calcCals()">
                                </div>
                                <div class="col">
                                    <label>Reps/Mins</label>
                                    <input type="number" name="reps" id="reps" class="form-control text-white" value="10" oninput="calcCals()">
                                </div>
                                <div class="col">
                                    <label>Calories</label>
                                    <input type="number" name="calories" id="calories" class="form-control text-white" value="45">
                                </div>
                            </div>
                            <button class="btn btn-green btn-sm w-100 mt-2">Log It</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Recent Activity</div>
                    <div class="table-responsive">
                        <table class="table table-dark-custom mb-0">
                            <thead><tr><th>Date</th><th>Exercise</th><th>Sets</th><th>Reps</th><th>Calories</th></tr></thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $row['date'] ?></td>
                                    <td><?= $row['exercise'] ?></td>
                                    <td><?= $row['sets'] ?></td>
                                    <td><?= $row['reps'] ?></td>
                                    <td><?= $row['calories'] ?? 0 ?> kcal</td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <a href="member_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
            </div>
        </div>

    </div>
</div>
<script>
function calcCals() {
    // 10 reps, 4 sets, Bench Press = 250 cal targeted.
    // 40 total reps -> 250 / 40 = 6.25 per rep.
    const multipliers = {
        'Bench Press': 6.25,
        'Squat': 6.5,
        'Deadlift': 7.0,
        'Overhead Press': 5.5,
        'Run (Treadmill)': 15.0 // For treadmill, input is in 'Mins' typically, so this is 15 cal/min.
    };
    let ex = document.getElementById('exercise').value;
    let sets = parseInt(document.getElementById('sets').value) || 0;
    let reps = parseInt(document.getElementById('reps').value) || 0;
    let mult = multipliers[ex] || 5.0;
    
    // For running, sets might be meaningless, so we just do reps(mins) * mult, or standard sets*reps*mult
    if (ex === 'Run (Treadmill)') {
        document.getElementById('calories').value = Math.round(reps * mult);
    } else {
        document.getElementById('calories').value = Math.round(sets * reps * mult);
    }
}
// Initialize on load
document.addEventListener("DOMContentLoaded", calcCals);
</script>
</body>
</html>
