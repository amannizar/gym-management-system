<?php
session_start();
require_once "backend/db.php";

if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit;
}

$user_id = $_SESSION['member_id'];

// Check if already filled
$check = mysqli_query($conn, "SELECT id FROM member_health WHERE member_id = '$user_id'");
if (mysqli_num_rows($check) > 0) {
    header("Location: member_dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diabetes = $_POST['diabetes'];
    $heart = $_POST['heart_condition'];
    $asthma = $_POST['asthma'];
    $injuries = mysqli_real_escape_string($conn, $_POST['injuries']);
    $other = mysqli_real_escape_string($conn, $_POST['other_info']);

    $sql = "INSERT INTO member_health (member_id, diabetes, heart_condition, asthma, injuries, other_info) 
            VALUES ('$user_id', '$diabetes', '$heart', '$asthma', '$injuries', '$other')";

    if (mysqli_query($conn, $sql)) {
        header("Location: member_dashboard.php");
        exit;
    } else {
        $error = "Error saving health data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Declaration - GymPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0a0a0a;
            color: white;
            padding-top: 50px;
        }
        .health-card {
            background: rgba(30, 30, 30, 0.9);
            border: 1px solid #333;
            border-radius: 15px;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 255, 163, 0.1);
        }
        .form-label {
            color: #ccc;
            font-weight: 500;
        }
        .form-select, .form-control {
            background: #111;
            border: 1px solid #444;
            color: white;
        }
        .form-select:focus, .form-control:focus {
            background: #111;
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(0, 255, 163, 0.5);
        }
        .btn-submit {
            background: var(--primary-color);
            color: #000;
            font-weight: bold;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-submit:hover {
            background: #00cc82;
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="health-card">
        <h2 class="text-center mb-4" style="color: var(--primary-color);">Health Declaration 🩺</h2>
        <p class="text-center text-secondary mb-4">Please fill out this form to help our AI Coach better assist you.</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Diabetes?</label>
                    <select name="diabetes" class="form-select" required>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Heart Condition?</label>
                    <select name="heart_condition" class="form-select" required>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Asthma?</label>
                    <select name="asthma" class="form-select" required>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Any Previous Injuries?</label>
                <textarea name="injuries" class="form-control" rows="2" placeholder="e.g., ACL tear, Shoulder dislocation..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Other Medical Info (Allergies, etc.)</label>
                <textarea name="other_info" class="form-control" rows="2" placeholder="Any other details..."></textarea>
            </div>

            <button type="submit" class="btn-submit">SUBMIT & CONTINUE</button>
        </form>
    </div>
</div>

</body>
</html>
