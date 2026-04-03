<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "backend/db.php";

// Auth Check
if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit;
}

$member_id = $_SESSION['member_id'];
// Handle Manual Sleep Entry
if (isset($_POST['save_manual_sleep'])) {
    $hours = filter_input(INPUT_POST, 'hours', FILTER_VALIDATE_FLOAT);
    $deep = filter_input(INPUT_POST, 'deep', FILTER_VALIDATE_FLOAT);
    $rem = filter_input(INPUT_POST, 'rem', FILTER_VALIDATE_FLOAT);

    $json_data = json_encode([
        'hours' => $hours ?: 0,
        'deep' => $deep ?: 0,
        'rem' => $rem ?: 0
    ]);

    $stmt = $conn->prepare("UPDATE health_metrics SET last_sleep_data = ? WHERE member_id = ?");
    $stmt->bind_param("si", $json_data, $member_id);
    $stmt->execute();
    
    header("Location: sleep_monitor.php");
    exit;
}

$health = mysqli_fetch_assoc(mysqli_query($conn, "SELECT last_sleep_data, target_sleep FROM health_metrics WHERE member_id=$member_id"));
$target = $health['target_sleep'] ?? 8.0;
$last_data = json_decode($health['last_sleep_data'] ?? '{"hours":0, "deep":0, "rem":0}', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sleep Monitor 🛌</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .sleep-circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 10px solid #222;
            border-top: 10px solid var(--primary-color); 
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin: 0 auto;
            position: relative;
        }
        .sleep-val { font-size: 3rem; font-weight: bold; }
        .iot-status { color: var(--secondary-color); font-size: 0.9rem; margin-top: 10px; }
        .blink { animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
        /* Accordion Custom Style */
        .accordion-button { background-color: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1); }
        .accordion-button:not(.collapsed) { background-color: rgba(255,255,255,0.1); color: var(--primary-color); box-shadow: none; }
        .accordion-button:focus { box-shadow: none; }
        .accordion-item { background: transparent; border: none; }
        .accordion-body { background: rgba(0,0,0,0.2); border-radius: 0 0 10px 10px; }
    </style>
</head>
<body>
<?php require_once "backend/member_nav.php"; ?>
<div class="page d-block">
    <div class="container py-4 text-center" style="padding-top: 60px;">
        
        <h2 class="mb-4">Sleep Monitor 🛌</h2>
        
        <div class="mb-3 text-start">
            <a href="member_dashboard.php" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="sleep-circle" id="sleepRing">
                        <div class="sleep-val text-white" id="sleepHours"><?= $last_data['hours'] ?>h</div>
                        <div class="text-white-50">Sleep Duration</div>
                    </div>

                    <div class="row mt-4 text-center">
                        <div class="col-6">
                            <h5>Deep Sleep</h5>
                            <span class="text-info h4" id="deepSleep"><?= $last_data['deep'] ?>h</span>
                        </div>
                        <div class="col-6">
                            <h5>REM Cycles</h5>
                            <span class="text-warning h4" id="remSleep"><?= $last_data['rem'] ?>h</span>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color:rgba(255,255,255,0.1);">

                    <div class="iot-section mb-4">
                        <p class="text-muted mb-2">Connect your Smart Device</p>
                        <button onclick="syncIoT()" class="btn btn-outline-light w-100" id="syncBtn">
                            📡 Sync with Watch / Band
                        </button>
                        <div id="iotStatus" class="iot-status" style="display:none;">
                            <span class="blink">●</span> Syncing data...
                        </div>
                    </div>

                    <!-- Manual Entry Accordion -->
                    <div class="accordion" id="manualEntryAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manualForm">
                                    📝 Or Enter Manually
                                </button>
                            </h2>
                            <div id="manualForm" class="accordion-collapse collapse" data-bs-parent="#manualEntryAccordion">
                                <div class="accordion-body text-start">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Total Duration (Hours)</label>
                                            <input type="number" step="0.1" name="hours" class="form-control bg-dark border-secondary text-light" placeholder="e.g. 7.5" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <label class="form-label small text-muted">Deep Sleep</label>
                                                <input type="number" step="0.1" name="deep" class="form-control bg-dark border-secondary text-light" placeholder="e.g. 2.0">
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label class="form-label small text-muted">REM Sleep</label>
                                                <input type="number" step="0.1" name="rem" class="form-control bg-dark border-secondary text-light" placeholder="e.g. 1.5">
                                            </div>
                                        </div>
                                        <button type="submit" name="save_manual_sleep" class="btn btn-primary w-100">Update Sleep Log</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Manual Entry -->

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function syncIoT() {
        let btn = document.getElementById('syncBtn');
        let status = document.getElementById('iotStatus');
        
        btn.disabled = true;
        btn.innerHTML = "Connecting...";
        status.style.display = "block";

        // Simulate IoT Delay
        setTimeout(() => {
            // Random Sleep Data Generation
            let hours = (Math.random() * (9 - 5) + 5).toFixed(1);
            let deep = (Math.random() * (3 - 1) + 1).toFixed(1);
            let rem = (Math.random() * (2 - 0.5) + 0.5).toFixed(1);

            document.getElementById('sleepHours').innerText = hours + "h";
            document.getElementById('deepSleep').innerText = deep + "h";
            document.getElementById('remSleep').innerText = rem + "h";
            
            // Update Ring Color based on quality
            let ring = document.getElementById('sleepRing');
            if(hours >= 7) ring.style.borderTopColor = "#00ffa3"; // Good
            else ring.style.borderTopColor = "#ff4757"; // Bad

            btn.innerHTML = "✅ Synced Successfully";
            status.style.display = "none";
            
            // In a real app, we would POST this data to backend here
        }, 2000);
    }
</script>
</body>
</html>
