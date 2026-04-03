<?php
require_once "backend/db.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['member_id'])) { header("Location: member_login.php"); exit; }

$member_id = $_SESSION['member_id'];
$msg = "";

// Self-Healing: Ensure DB columns exist
try {
    $cols = mysqli_fetch_all(mysqli_query($conn, "SHOW COLUMNS FROM health_metrics LIKE 'diet_type'"), MYSQLI_ASSOC);
    if (empty($cols)) {
        mysqli_query($conn, "ALTER TABLE health_metrics ADD COLUMN diet_type ENUM('Veg', 'Non-Veg', 'Vegan') DEFAULT NULL");
        mysqli_query($conn, "ALTER TABLE health_metrics ADD COLUMN diet_goal ENUM('Weight Loss', 'Muscle Gain', 'Maintenance') DEFAULT NULL");
        mysqli_query($conn, "ALTER TABLE health_metrics ADD COLUMN food_allergies TEXT DEFAULT NULL");
    }
} catch (Exception $e) { /* Ignore */ }

// 1. Handle Questionnaire Submission
if (isset($_POST['save_diet_pref'])) {
    $diet_type = $_POST['diet_type'];
    $diet_goal = $_POST['diet_goal'];
    $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);

    // Check if record exists
    $check = mysqli_query($conn, "SELECT id FROM health_metrics WHERE member_id=$member_id");
    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE health_metrics SET diet_type='$diet_type', diet_goal='$diet_goal', food_allergies='$allergies' WHERE member_id=$member_id";
    } else {
        $sql = "INSERT INTO health_metrics (member_id, diet_type, diet_goal, food_allergies) VALUES ($member_id, '$diet_type', '$diet_goal', '$allergies')";
    }
    mysqli_query($conn, $sql);
    header("Location: diet.php"); // Reload to show plan
    exit;
}

// 1.5 Handle Reset
if (isset($_POST['reset_diet'])) {
    $sql = "UPDATE health_metrics SET diet_type=NULL, diet_goal=NULL WHERE member_id=$member_id";
    mysqli_query($conn, $sql);
    header("Location: diet.php");
    exit;
}

// 2. Fetch User Preferences
$metrics = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM health_metrics WHERE member_id=$member_id"));
$diet_type = $metrics['diet_type'] ?? null;
$diet_goal = $metrics['diet_goal'] ?? 'Maintenance';

// 3. Define Logic for Plans
function getPlan($type, $goal) {
    // Basic Logic for demo. In real app, this could be from a 'meal_plans' table.
    $plans = [
        'Veg' => [
            'Breakfast' => ['Oats & Nuts', 'Poha with Peanuts', 'Paneer Paratha'],
            'Lunch' => ['Dal Tadka & Rice', 'Palak Paneer', 'Chole Bhature (Cheat)'],
            'Dinner' => ['Roti & Mix Veg', 'Khichdi', 'Veg Salad']
        ],
        'Non-Veg' => [
            'Breakfast' => ['Boiled Eggs & Toast', 'Chicken Sausage', 'Omelette'],
            'Lunch' => ['Grilled Chicken', 'Fish Curry & Rice', 'Egg Biryani'],
            'Dinner' => ['Chicken Salad', 'Grilled Fish', 'Lemon Chicken']
        ],
        'Vegan' => [
            'Breakfast' => ['Smoothie Bowl', 'Avocado Toast', 'Tofu Scramble'],
            'Lunch' => ['Lentil Soup', 'Soy Chunks Curry', 'Quinoa Salad'],
            'Dinner' => ['Stir Fry Tofu', 'Vegan Burrito', 'Chickpea Salad']
        ]
    ];
    
    // Adjust portion/calories based on goal (Text only for now)
    $advice = match($goal) {
        'Weight Loss' => "Focus on high protein, low carb. Calorie Deficit: 500 kcal.",
        'Muscle Gain' => "High carb, high protein. Calorie Surplus: 300 kcal.",
        default => "Balanced macros for maintenance."
    };

    return ['menu' => $plans[$type] ?? $plans['Veg'], 'advice' => $advice];
}

$generated_plan = $diet_type ? getPlan($diet_type, $diet_goal) : null;

// Helper link
function getZomatoLink($food) {
    return "https://www.zomato.com/search?q=" . urlencode($food);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Diet Plan 🥗</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .meal-card { background: var(--card-bg); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; margin-bottom: 20px; overflow: hidden; transition: 0.3s; }
        .meal-card:hover { transform: translateY(-3px); border-color: var(--primary-color); }
        .meal-img { height: 120px; background-size: cover; background-position: center; }
        .meal-body { padding: 15px; }
        .step-circle { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: black; font-weight: bold; display: flex; align-items: center; justify-content: center; margin-right: 15px; }
    </style>
</head>
<body>
<?php require_once "backend/member_nav.php"; ?>
<div class="page d-block">
    <div class="container py-4">
        
        <h2 class="text-center mb-4 text-success">Nutrition & Diet 🥗</h2>

        <?php if (!$diet_type): ?>
            <!-- STEP 1: QUESTIONNAIRE -->
            <div class="card bg-dark border-success p-4 mx-auto" style="max-width: 600px;">
                <h4 class="text-center mb-3">Let's Customize Your Plan!</h4>
                <p class="text-muted text-center mb-4">Tell us about your preferences to generate the perfect meal plan.</p>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Are you Veg or Non-Veg?</label>
                        <div class="d-flex gap-3">
                            <label class="btn btn-outline-success flex-fill">
                                <input type="radio" name="diet_type" value="Veg" required> 🥬 Veg
                            </label>
                            <label class="btn btn-outline-danger flex-fill">
                                <input type="radio" name="diet_type" value="Non-Veg" required> 🍗 Non-Veg
                            </label>
                            <label class="btn btn-outline-warning flex-fill">
                                <input type="radio" name="diet_type" value="Vegan" required> 🥦 Vegan
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">What is your primary goal?</label>
                        <select name="diet_goal" class="form-select bg-dark text-light">
                            <option value="Weight Loss">🔥 Weight Loss</option>
                            <option value="Muscle Gain">💪 Muscle Gain</option>
                            <option value="Maintenance">⚖️ Maintain Weight</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Any Food Allergies? (Optional)</label>
                        <input type="text" name="allergies" class="form-control bg-dark text-light border-secondary" placeholder="e.g. Peanuts, Lactose...">
                    </div>

                    <button type="submit" name="save_diet_pref" class="btn btn-success w-100 fw-bold">Generate Plan ✨</button>
                </form>
            </div>

        <?php else: ?>
            <!-- STEP 2: DISPLAY PLAN -->
            <div class="text-center mb-4">
                <span class="badge bg-success fs-6 mb-2"><?= $diet_type ?> Plan</span>
                <span class="badge bg-secondary fs-6 mb-2"><?= $diet_goal ?></span>
                <br>
                <small class="text-muted"><?= $generated_plan['advice'] ?></small>
            </div>

            <div class="row">
                <!-- Breakfast -->
                <div class="col-md-4">
                    <div class="meal-card">
                        <div class="meal-img" style="background-image: url('https://images.unsplash.com/photo-1493770348161-369560ae357d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80');"></div>
                        <div class="meal-body">
                            <h5>☕ Breakfast</h5>
                            <ul class="text-white ps-3 mb-3">
                                <?php foreach($generated_plan['menu']['Breakfast'] as $item): ?>
                                    <li><?= $item ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?= getZomatoLink('Healthy Breakfast') ?>" target="_blank" class="btn btn-sm btn-outline-danger w-100">Order Online</a>
                        </div>
                    </div>
                </div>

                <!-- Lunch -->
                <div class="col-md-4">
                    <div class="meal-card">
                        <div class="meal-img" style="background-image: url('https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');"></div>
                        <div class="meal-body">
                            <h5>🍛 Lunch</h5>
                            <ul class="text-white ps-3 mb-3">
                                <?php foreach($generated_plan['menu']['Lunch'] as $item): ?>
                                    <li><?= $item ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?= getZomatoLink('Healthy Lunch') ?>" target="_blank" class="btn btn-sm btn-outline-danger w-100">Order Online</a>
                        </div>
                    </div>
                </div>

                <!-- Dinner -->
                <div class="col-md-4">
                    <div class="meal-card">
                        <div class="meal-img" style="background-image: url('https://images.unsplash.com/photo-1467003909585-2f8a7270028d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');"></div>
                        <div class="meal-body">
                            <h5>🌙 Dinner</h5>
                            <ul class="text-white ps-3 mb-3">
                                <?php foreach($generated_plan['menu']['Dinner'] as $item): ?>
                                    <li><?= $item ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?= getZomatoLink('Healthy Dinner') ?>" target="_blank" class="btn btn-sm btn-outline-danger w-100">Order Online</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reset Option -->
            <div class="text-center mt-5">
                <form method="POST">
                    <input type="hidden" name="diet_type" value=""> <!-- Clear it roughly or add reset logic -->
                    <button type="submit" name="reset_diet" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Reset preferences?')">🔄 Change Preferences</button>
                    <!-- Logic for reset needs to be added to top PHP block -->
                </form>
            </div>
            
        <?php endif; ?>

    </div>
</div>
</body>
</html>
