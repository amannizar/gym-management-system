<?php
session_start();
require_once "backend/db.php";
require_once "backend/setup_admin_tables.php"; // Self-healing: Ensure tables exist
$member_id = $_SESSION['member_id'];

// Check Health Form Status
$healthCheck = mysqli_query($conn, "SELECT id FROM member_health WHERE member_id = '$member_id'");
if (mysqli_num_rows($healthCheck) == 0) {
    header("Location: health_declaration.php");
    exit;
}

require_once "backend/member_nav.php";

// Fetch Health Data
$health = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM health_metrics WHERE member_id=$member_id"));
$bmi = 0;
$bmi_color = 'text-muted';
$bmi_text = 'N/A';

if ($health && $health['height_cm'] > 0 && $health['weight_kg'] > 0) {
    $height_m = $health['height_cm'] / 100;
    $bmi = round($health['weight_kg'] / ($height_m * $height_m), 1);
    
    if ($bmi < 18.5) { $bmi_text = 'Underweight'; $bmi_color='text-warning'; }
    elseif ($bmi < 25) { $bmi_text = 'Healthy'; $bmi_color='text-success'; }
    elseif ($bmi < 30) { $bmi_text = 'Overweight'; $bmi_color='text-warning'; }
    else { $bmi_text = 'Obese'; $bmi_color='text-danger'; }
}

// Goal Progress
$goal_progress = 0;
if ($health && $health['goal_weight_kg'] > 0) {
    // Simple logic: Assuming weight loss
    // $goal_progress = ... (complex logic omitted for simplicity, just showing current/goal)
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
        }
        .feature-card {
            background: var(--card-bg);
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s;
            border: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            color: var(--text-color);
        }
        .feature-img {
            height: 150px;
            background-size: cover;
            background-position: center;
        }
        .feature-content {
            padding: 20px;
            flex: 1;
        }
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .feature-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        .bmi-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(0,0,0,0.2));
            border-radius: 20px;
            padding: 20px;
            margin: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .bmi-value { font-size: 3rem; font-weight: 700; color: white; }
    </style>
</head>
<body>

<!-- Background Quote -->
<div id="bg-quote" class="background-quote">EARN<br>IT</div>

<script>
    const quotes = [
        "EARN<br>IT",
        "FOCUS<br>ON YOU",
        "SWEAT<br>IS MAGIC",
        "DONT<br>QUIT",
        "ONE MORE<br>REP"
    ];
    let quoteIdx = 0;
    setInterval(() => {
        const el = document.getElementById('bg-quote');
        el.style.opacity = 0;
        setTimeout(() => {
            quoteIdx = (quoteIdx + 1) % quotes.length;
            el.innerHTML = quotes[quoteIdx];
            el.style.opacity = 1;
        }, 2000);
    }, 10000);
</script>

<div class="page d-block">
    <div class="container py-4">
        <!-- Re-use the navbar logic included at top -->
        
        <!-- Announcement Banner -->
        <?php
        $latest_announcement = mysqli_fetch_assoc(mysqli_query($conn, "SELECT message FROM announcements ORDER BY created_at DESC LIMIT 1"));
        if ($latest_announcement): 
        ?>
        <div class="alert alert-info border-info bg-dark text-info shadow d-flex align-items-center mb-4 fade show" role="alert">
            <span class="fs-2 me-3">📢</span>
            <div>
                <h5 class="alert-heading fw-bold mb-1">New Announcement</h5>
                <p class="mb-0"><?= htmlspecialchars($latest_announcement['message']) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Health Status Banner -->
        <div class="bmi-card">
            <div>
                <h4 class="text-white-50 mb-0">Your BMI Score</h4>
                <div class="bmi-value"><?= $bmi ?></div>
                <div class="<?= $bmi_color ?> fw-bold"><?= $bmi_text ?></div>
                
                <?php
                // Fetch Subscription
                $mem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT subscription_end FROM members WHERE id=$member_id"));
                $days_left = 0;
                if ($mem['subscription_end']) {
                    $end_date = new DateTime($mem['subscription_end']);
                    $today = new DateTime();
                    $interval = $today->diff($end_date);
                    $days_left = (int)$interval->format('%r%a');
                }
                ?>
                <div class="mt-3 text-warning">
                    Membership: 
                    <strong><?= $days_left > 0 ? $days_left . " Days Left" : "Expired" ?></strong>
                    <br>
                    <small style="font-size:0.7em; color:#ddd;">(Ends on: <?= $mem['subscription_end'] ?? 'N/A' ?>)</small>
                    <br>
                    <a href="subscription.php" class="btn btn-sm btn-success mt-1">Renew Now 💎</a>
                </div>
            </div>
            <div class="text-end">
                <div class="text-white-50">Current Weight</div>
                <div class="h3"><?= $health['weight_kg'] ?? '--' ?> kg</div>
                <div class="text-white-50">Goal: <?= $health['goal_weight_kg'] ?? '--' ?> kg</div>
                <a href="profile.php" class="btn btn-sm btn-outline-light mt-2">Update Profile</a>
            </div>
        </div>

        <div class="feature-grid">
            
            <!-- AI Coach -->
            <a href="ai_coach.php" class="feature-card">
                <div class="feature-img" style="background-image: url('https://images.unsplash.com/photo-1531746790731-6c087fecd65a?q=80&w=1406&auto=format&fit=crop');"></div>
                <div class="feature-content">
                    <div class="feature-title">🤖 AI Coach</div>
                    <p class="feature-desc">Get personalized workout plans and advice instantly.</p>
                </div>
            </a>

            <!-- Workout Logger -->
            <a href="workouts.php" class="feature-card">
                <div class="feature-img" style="background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop');"></div>
                <div class="feature-content">
                    <div class="feature-title">🏋️ Workout Log</div>
                    <p class="feature-desc">Track your progress, sets, and reps.</p>
                </div>
            </a>

            <!-- Diet Plan -->
            <a href="diet.php" class="feature-card">
                <div class="feature-img" style="background-image: url('https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=1453&auto=format&fit=crop');"></div>
                <div class="feature-content">
                    <div class="feature-title">🥗 Diet Plan</div>
                    <p class="feature-desc">View your assigned meal plans and calorie goals.</p>
                </div>
            </a>

            <!-- IoT Devices -->
            <a href="iot.php" class="feature-card">
                <div class="feature-img" style="background-image: url('https://images.unsplash.com/photo-1510017803434-a899398421b3?q=80&w=1470&auto=format&fit=crop');"></div>
                <div class="feature-content">
                    <div class="feature-title">⌚ IoT Devices</div>
                    <p class="feature-desc">Sync your smart watch and health monitors.</p>
                </div>
            </a>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
