<?php
require_once "backend/member_nav.php";
require_once "backend/db.php";

$member_id = $_SESSION['member_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE id=$member_id"));

$days_left = 0;
if ($user['subscription_end']) {
    $end_date = new DateTime($user['subscription_end']);
    $today = new DateTime();
    $interval = $today->diff($end_date);
    $days_left = (int)$interval->format('%r%a');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .plan-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .plan-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-color);
        }
        .plan-price { font-size: 2.5rem; font-weight: 700; color: white; margin: 15px 0; }
        .plan-name { font-size: 1.5rem; font-weight: 600; color: var(--primary-color); }
        .plan-features { list-style: none; padding: 0; margin: 20px 0; flex: 1; text-align: left; }
        .plan-features li { margin-bottom: 10px; color: var(--text-muted); }
        .plan-features li::before { content: "✓"; color: var(--primary-color); margin-right: 10px; }
        
        .status-box {
            background: linear-gradient(45deg, #1e1e1e, #2a2a2a);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="page d-block">
    <div class="container py-4">

        <!-- Status Banner -->
        <div class="status-box">
            <div>
                <h4 class="mb-1">Current Membership</h4>
                <div class="text-muted">Status: 
                    <?php if($days_left > 0): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Expired</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-end">
                <div class="display-6 fw-bold <?= $days_left < 5 ? 'text-danger' : 'text-white' ?>">
                    <?= $days_left > 0 ? $days_left . " Days Left" : "Start Now" ?>
                </div>
                <small class="text-muted">Expires: <?= $user['subscription_end'] ?? 'N/A' ?></small>
            </div>
        </div>

        <h2 class="text-center mb-5">Select Your Plan</h2>

        <div class="row g-4">
            
            <!-- Silver -->
            <div class="col-md-4">
                <div class="plan-card">
                    <div class="plan-name">Silver Plan</div>
                    <div class="plan-price">₹1,500 <small class="fs-6 text-muted">/mo</small></div>
                    <ul class="plan-features">
                        <li>Access to Gym Equipment</li>
                        <li>Locker Access</li>
                        <li>Free Wifi</li>
                        <li>1 Month Validity</li>
                    </ul>
                    <a href="payment.php?plan=1&name=Silver&price=1500" class="btn btn-outline-light w-100 mt-auto">Choose Silver</a>
                </div>
            </div>

            <!-- Gold -->
            <div class="col-md-4">
                <div class="plan-card" style="border-color: var(--primary-color); box-shadow: 0 0 20px rgba(0,255,163,0.1);">
                    <div class="badge bg-success mb-2 w-50 mx-auto">Most Popular</div>
                    <div class="plan-name">Gold Plan</div>
                    <div class="plan-price">₹7,500 <small class="fs-6 text-muted">/6mo</small></div>
                    <ul class="plan-features">
                        <li>All Silver Features</li>
                        <li><strong>AI Coach Access</strong></li>
                        <li>Diet Plan Included</li>
                        <li>6 Months Validity</li>
                    </ul>
                    <a href="payment.php?plan=2&name=Gold&price=7500" class="btn btn-green w-100 mt-auto">Choose Gold</a>
                </div>
            </div>

            <!-- Platinum -->
            <div class="col-md-4">
                <div class="plan-card">
                    <div class="plan-name">Platinum Plan</div>
                    <div class="plan-price">₹12,000 <small class="fs-6 text-muted">/yr</small></div>
                    <ul class="plan-features">
                        <li>All Gold Features</li>
                        <li>Personal Trainer Sessions</li>
                        <li>Sauna Access</li>
                        <li><strong>1 Year Validity</strong></li>
                    </ul>
                    <a href="payment.php?plan=3&name=Platinum&price=12000" class="btn btn-outline-light w-100 mt-auto">Choose Platinum</a>
                </div>
            </div>

        </div>

    </div>
</div>
</body>
</html>
