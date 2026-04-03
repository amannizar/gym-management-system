<?php
require_once "backend/db.php";

// Simulate Subscription Check
$is_premium = isset($_GET['subscribed']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Premium Diet Plans 💎</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .blur-content { filter: blur(5px); pointer-events: none; user-select: none; }
        .paywall-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 10;
        }
    </style>
</head>
<body>
<?php require_once "backend/member_nav.php"; ?>
<div class="page d-block">
    <div class="container py-4">
        
        <h2 class="mb-4 text-center text-primary">Monthly Diet Plan (Premium) 💎</h2>

        <div class="mb-3">
            <a href="member_dashboard.php" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
        </div>

        <div class="card position-relative p-0 overflow-hidden">
            
            <?php if(!$is_premium): ?>
            <div class="paywall-overlay">
                <h3 class="text-white mb-3">🔒 Premium Content</h3>
                <p class="text-light mb-4">Unlock your personalized 30-Day Meal Plan monitored by experts.</p>
                <div class="h2 text-warning mb-4">₹999 / month</div>
                <a href="?subscribed=true" class="btn btn-success btn-lg">Subscribe Now 💳</a>
            </div>
            <?php endif; ?>

            <div class="card-body <?= $is_premium ? '' : 'blur-content' ?>">
                <h4 class="mb-3">Week 1 Plan</h4>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Monday</td><td>Oats & Berries</td><td>Grilled Chicken Salad</td><td>Steamed Fish & Veg</td></tr>
                        <tr><td>Tuesday</td><td>Scrambled Eggs</td><td>Quinoa Bowl</td><td>Paneer Tikka</td></tr>
                        <tr><td>Wednesday</td><td>Smoothie</td><td>Brown Rice & Dal</td><td>Chicken Soup</td></tr>
                        <tr><td>Thursday</td><td>Pancakes (Protein)</td><td>Tuna Sandwich</td><td>Stir Fry Tofu</td></tr>
                        <tr><td>Friday</td><td>Avocado Toast</td><td>Pasta (Whole Wheat)</td><td>Lean Steak</td></tr>
                    </tbody>
                </table>

                <div class="alert alert-info mt-3">
                    <strong>Instructor Note:</strong> Keep hydration above 3L this week!
                </div>
            </div>

        </div>

    </div>
</div>
</body>
</html>
