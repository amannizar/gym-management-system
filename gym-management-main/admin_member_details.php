<?php
require_once "backend/auth_check.php";
require_once "backend/db.php";

$id = $_GET['id'] ?? 0;
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE id=$id"));
$health = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM health_metrics WHERE member_id=$id"));

if (!$user) {
    die("Member not found.");
}

// Subscription Status Logic
$status_badge = '<span class="badge bg-secondary">Inactive</span>';
if ($user['subscription_end']) {
    $end = new DateTime($user['subscription_end']);
    $now = new DateTime();
    if ($end > $now) {
        $status_badge = '<span class="badge bg-success">Active</span>';
    } else {
        $status_badge = '<span class="badge bg-danger">Expired</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Details - Secure View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .detail-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
        }
        .label {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .value {
            font-size: 1.1rem;
            color: white;
            font-weight: 500;
        }
        .avatar-circle {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin-right: 20px;
        }
    </style>
</head>
<body>
<div class="page d-block">
    <div class="container py-4">
        
        <div class="d-flex align-items-center mb-4">
            <div class="avatar-circle">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <div>
                <h2 class="mb-0"><?= htmlspecialchars($user['name']) ?></h2>
                <div class="text-muted">Member ID: #<?= $user['id'] ?></div>
            </div>
        </div>

        <div class="row">
            <!-- Col 1: Personal Info -->
            <div class="col-md-6">
                <div class="detail-card">
                    <h4 class="mb-4 text-primary">👤 Personal Info</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="label">Email Address</div>
                            <div class="value"><?= htmlspecialchars($user['email']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="label">Phone Number</div>
                            <div class="value"><?= htmlspecialchars($user['phone']) ?></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="label">Joined Date</div>
                            <div class="value"><?= $user['created_at'] ?? 'Recently' ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="label">Account Status</div>
                            <div class="value">Verified ✅</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Subscription -->
            <div class="col-md-6">
                <div class="detail-card">
                    <h4 class="mb-4 text-warning">💎 Subscription</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="label">Current Status</div>
                            <div class="value"><?= $status_badge ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="label">Plan Type</div>
                            <div class="value">
                                <?php 
                                    $plans = [0=>'None', 1=>'Silver', 2=>'Gold', 3=>'Platinum'];
                                    echo $plans[$user['plan_id'] ?? 0];
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="label">Subscription Value</div>
                        <div class="value">
                            From: <?= $user['subscription_start'] ?? 'N/A' ?> <br>
                            To: <?= $user['subscription_end'] ?? 'N/A' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Health Stats -->
        <div class="detail-card">
            <h4 class="mb-4 text-success">🩺 Health Snapshot</h4>
            <?php if($health): ?>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="label">Height</div>
                        <div class="display-6"><?= $health['height_cm'] ?>cm</div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Weight</div>
                        <div class="display-6"><?= $health['weight_kg'] ?>kg</div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Age</div>
                        <div class="display-6"><?= $health['age'] ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="label">Goal</div>
                        <div class="h4 text-success mt-2"><?= $health['goal_weight_kg'] ?>kg</div>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">No health data recorded yet.</p>
            <?php endif; ?>
        </div>

        <!-- Row 3: Extended Details -->
        <div class="detail-card mt-4">
            <h4 class="mb-4 text-info">📋 Extended Details</h4>
            <div class="row">
                <div class="col-md-3">
                    <div class="label">Gender</div>
                    <div class="value"><?= $user['gender'] ?? '-' ?></div>
                </div>
                <div class="col-md-3">
                    <div class="label">Date of Birth</div>
                    <div class="value"><?= $user['dob'] ?? '-' ?></div>
                </div>
                <div class="col-md-3">
                    <div class="label">Blood Group</div>
                    <div class="value text-danger fw-bold"><?= $user['blood_group'] ?? '-' ?></div>
                </div>
                <div class="col-md-3">
                    <div class="label">Emergency Contact</div>
                    <div class="value text-warning"><?= $user['emergency_contact'] ?? '-' ?></div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="label">Address</div>
                    <div class="value"><?= htmlspecialchars($user['address'] ?? 'Not Provided') ?></div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="view_members.php" class="btn btn-secondary">Back to List</a>
        </div>

    </div>
</div>
</body>
</html>
