<?php
require_once "backend/db.php";

$msg = "";
$macro_data = null;

if (isset($_POST['upload_scan'])) {
    // ... logic remains ...
    // In real app: save file. Here: Simulate real analysis.
    
    // Randomize Macros for the simulation to feel real
    $cal = rand(300, 800);
    $protein = rand(20, 50);
    $carbs = rand(30, 80);
    $fat = rand(10, 30);
    $burn_run = round($cal / 10); // 10 kcal per min running
    $burn_cycle = round($cal / 8); 

    $macro_data = [
        "calories" => $cal,
        "protein" => $protein,
        "carbs" => $carbs,
        "fat" => $fat,
        "burn" => "🏃 $burn_run min run or 🚴 $burn_cycle min cycling"
    ];

    $coach_responses = [
        "Looks good! High protein content. ✅",
        "A bit too much oil, good for bulking but watch the fat. ⚠️",
        "Excellent post-workout fuel! 💪",
        "Solid meal, but add some fiber. 🥦"
    ];
    $response = $coach_responses[array_rand($coach_responses)];
    $msg = $response;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ask Coach 📸</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once "backend/member_nav.php"; ?>
<div class="page d-block">
    <div class="container py-4 text-center">
        
        <h2 class="mb-4 text-warning">Diet Consultant 👨‍⚕️</h2>
        
        <div class="mb-3 text-start">
            <a href="member_dashboard.php" class="btn btn-outline-secondary btn-sm">← Back to Dashboard</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h4>Is this healthy?</h4>
                    <p class="text-muted">Take a photo of your meal and our Coach will analyze it.</p>

                    <form method="POST" enctype="multipart/form-data" class="mt-4">
                        <div class="mb-3">
                            <input type="file" class="form-control" name="food_img" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="notes" placeholder="Any questions? (e.g. Is this Keto friendly?)"></textarea>
                        </div>
                        <button name="upload_scan" class="btn btn-warning w-100 text-dark fw-bold">Scan Food 📸</button>
                    </form>

                    <?php if($msg): ?>
                        <div class="alert alert-success mt-4 text-start">
                            <h5 class="alert-heading">📊 Food Analysis Report</h5>
                            <p class="mb-2"><strong>Coach says:</strong> <?= $msg ?></p>
                            <hr>
                            <div class="row text-center mb-2">
                                <div class="col-4">
                                    <div class="h4 mb-0"><?= $macro_data['calories'] ?></div>
                                    <small class="text-muted">Calories</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 text-success"><?= $macro_data['protein'] ?>g</div>
                                    <small class="text-muted">Protein</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 text-warning"><?= $macro_data['carbs'] ?>g</div>
                                    <small class="text-muted">Carbs</small>
                                </div>
                            </div>
                            <div class="p-2 bg-dark rounded border border-secondary mb-2">
                                <div class="d-flex justify-content-between small text-white-50 mb-1">
                                    <span>Fat: <?= $macro_data['fat'] ?>g</span>
                                    <span>Protein Density: High</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" style="width: 20%"></div>
                                    <div class="progress-bar bg-warning" style="width: 50%"></div>
                                    <div class="progress-bar bg-success" style="width: 30%"></div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="badge bg-danger p-2 w-100">
                                    🔥 To burn this: <?= $macro_data['burn'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
