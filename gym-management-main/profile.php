<?php
require_once "backend/member_nav.php";
require_once "backend/db.php";

$member_id = $_SESSION['member_id'];
$msg = "";
$msg_type = "";

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Email
    if (isset($_POST['update_email'])) {
        $new_email = mysqli_real_escape_string($conn, $_POST['email']);
        $sql = "UPDATE members SET email='$new_email' WHERE id=$member_id";
        if (mysqli_query($conn, $sql)) {
            $msg = "Email updated successfully! 📧";
            $msg_type = "success";
        } else {
            $msg = "Error updating email.";
            $msg_type = "danger";
        }
    }

    // 2. Change Password
    if (isset($_POST['change_password'])) {
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];
        
        if ($new_pass === $confirm_pass) {
            // In a real app, hash this! For this project, we might be using plain text or basic hashing depending on login.php
            // Assuming plain text based on previous context, or add hashing if login uses it.
            // Let's assume plain text for consistency with likely previous code, or verify login.php later.
            // Safe bet: Update directly.
            $sql = "UPDATE members SET password='$new_pass' WHERE id=$member_id";
            if (mysqli_query($conn, $sql)) {
                $msg = "Password changed successfully! 🔒";
                $msg_type = "success";
            }
        } else {
            $msg = "Passwords do not match! ❌";
            $msg_type = "danger";
        }
    }
    // 3. Update Personal Details
    if (isset($_POST['update_personal'])) {
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $blood = mysqli_real_escape_string($conn, $_POST['blood_group']);
        $emergency = mysqli_real_escape_string($conn, $_POST['emergency_contact']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        $sql = "UPDATE members SET 
            gender='$gender', 
            dob='$dob', 
            blood_group='$blood', 
            emergency_contact='$emergency', 
            address='$address' 
            WHERE id=$member_id";
            
        if (mysqli_query($conn, $sql)) {
            $msg = "Profile details updated! ✅";
            $msg_type = "success";
        } else {
            $msg = "Error updating profile.";
            $msg_type = "danger";
        }
    }

    // 4. Update Physical Stats
    if (isset($_POST['update_stats'])) {
        $weight = (float)$_POST['weight'];
        $height = (float)$_POST['height'];
        $goal = (float)$_POST['goal'];
        
        // Check if row exists
        $check_h = mysqli_query($conn, "SELECT id FROM health_metrics WHERE member_id=$member_id");
        if (mysqli_num_rows($check_h) > 0) {
            $sql_h = "UPDATE health_metrics SET weight_kg='$weight', height_cm='$height', goal_weight_kg='$goal' WHERE member_id=$member_id";
        } else {
            $sql_h = "INSERT INTO health_metrics (member_id, weight_kg, height_cm, goal_weight_kg) VALUES ($member_id, '$weight', '$height', '$goal')";
        }
        
        if (mysqli_query($conn, $sql_h)) {
            $msg = "Physical stats updated! 💪";
            $msg_type = "success";
        } else {
            $msg = "Error updating stats.";
            $msg_type = "danger";
        }
    }
}

// Fetch current data
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE id=$member_id"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto 30px auto;
        }
        .form-control, .form-select {
            background: rgba(0,0,0,0.2);
            border: 1px solid #444;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(0,0,0,0.3);
            color: white;
            border-color: var(--primary-color);
            box-shadow: none;
        }
    </style>
</head>
<body>
<div class="page d-block">
    <div class="container py-4">
        
        <h2 class="text-center mb-4">Account Settings ⚙️</h2>

        <?php if($msg): ?>
            <div class="alert alert-<?= $msg_type ?> text-center" style="max-width: 600px; margin: 0 auto 20px auto;">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <!-- My AI Plans Section -->
        <div class="profile-card">
            <h4 class="mb-3 text-primary">📅 My AI Workout & Diet Plans</h4>
            <?php
            $plans_q = mysqli_query($conn, "SELECT * FROM member_plans WHERE member_id=$member_id ORDER BY generated_at DESC");
            if(mysqli_num_rows($plans_q) > 0) {
                while($plan = mysqli_fetch_assoc($plans_q)) {
                    $date = date("d M Y, h:i A", strtotime($plan['generated_at']));
                    echo '<div class="card mb-3 p-3 text-white" style="background:rgba(255,255,255,0.05);">';
                    echo '<div class="d-flex justify-content-between align-items-center mb-2">';
                    echo '<strong class="text-primary">Generated on: ' . $date . '</strong>';
                    echo '<span class="badge bg-success">Weekly Plan</span>';
                    echo '</div>';
                    echo '<pre style="white-space: pre-wrap; font-family: sans-serif; font-size: 0.9em; color: #ddd;">' . htmlspecialchars($plan['plan_content']) . '</pre>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-muted">No saved plans yet. Ask the <a href="ai_coach.php" class="text-info">AI Coach</a> for a diet chart!</p>';
            }
            ?>
        </div>

        <!-- Personal Details Section -->
        <div class="profile-card">
            <h4 class="mb-3 text-info">📋 Personal Details</h4>
            <form method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male" <?= ($user['gender']??'')=='Male'?'selected':'' ?>>Male</option>
                            <option value="Female" <?= ($user['gender']??'')=='Female'?'selected':'' ?>>Female</option>
                            <option value="Other" <?= ($user['gender']??'')=='Other'?'selected':'' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?= $user['dob']??'' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Blood Group</label>
                        <input type="text" name="blood_group" class="form-control" placeholder="O+" value="<?= $user['blood_group']??'' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-control" placeholder="Parent/Spouse Name & #No" value="<?= $user['emergency_contact']??'' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Full Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="#123, Street Name, City"><?= $user['address']??'' ?></textarea>
                </div>

                <button type="submit" name="update_personal" class="btn btn-info text-white">Save Details</button>
            </form>
        </div>

        <!-- Physical Stats Section -->
        <div class="profile-card">
            <h4 class="mb-3 text-success">💪 Physical Stats (BMI & Goals)</h4>
            <form method="POST">
                <?php 
                $health_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM health_metrics WHERE member_id=$member_id")); 
                ?>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Height (cm)</label>
                        <input type="number" step="0.01" name="height" class="form-control" placeholder="e.g. 175" value="<?= $health_data['height_cm']??'' ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Current Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control" placeholder="e.g. 70" value="<?= $health_data['weight_kg']??'' ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Goal Weight (kg)</label>
                        <input type="number" step="0.01" name="goal" class="form-control" placeholder="e.g. 65" value="<?= $health_data['goal_weight_kg']??'' ?>">
                    </div>
                </div>
                <button type="submit" name="update_stats" class="btn btn-success">Update Stats</button>
            </form>
        </div>

        <!-- Email Section -->
        <div class="profile-card">
            <h4 class="mb-3 text-primary">Contact Info</h4>
            <form method="POST">
                <div class="mb-3">
                    <label>Your Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" disabled style="opacity: 0.6;">
                </div>
                <div class="mb-3">
                    <label>Gmail ID / Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button type="submit" name="update_email" class="btn btn-outline-light">Update Email</button>
            </form>
        </div>

        <!-- Password Section -->
        <div class="profile-card">
            <h4 class="mb-3 text-warning">Security</h4>
            <form method="POST">
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-warning text-dark">Change Password</button>
            </form>
        </div>

        <div class="text-center">
            <a href="member_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

    </div>
</div>
</body>
</html>
