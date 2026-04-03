<?php
require_once "backend/db.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_pass = $_POST['password'];
    
    // Check if email exists
    $check = mysqli_query($conn, "SELECT id FROM members WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $sql = "UPDATE members SET password='$hashed_pass' WHERE email='$email'";
        
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>Password for <b>$email</b> has been reset successfully! <a href='member_login.php'>Login Now</a></div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Email not found!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Member Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1a1a1a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .card { background: #333; border: 1px solid #444; color: white; width: 100%; max-width: 400px; }
        .form-control { background: #222; border: 1px solid #444; color: white; }
        .form-control:focus { background: #222; color: white; border-color: #0d6efd; box-shadow: none; }
    </style>
</head>
<body>

<div class="card p-4">
    <h3 class="text-center mb-4">Reset Password 🔐</h3>
    
    <?= $message ?>

    <form method="POST">
        <div class="mb-3">
            <label>Member Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter your email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
        </div>
        
        <div class="mb-3">
            <label>New Password</label>
            <input type="text" name="password" class="form-control" required placeholder="Enter new password">
        </div>

        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
    
    <div class="mt-3 text-center">
        <a href="member_login.php" class="text-decoration-none text-white-50">Back to Login</a>
    </div>

    <?php
    // List available emails for convenience in dev
    $users = mysqli_query($conn, "SELECT email FROM members LIMIT 50");
    if (mysqli_num_rows($users) > 0) {
        echo "<hr><small class='text-muted'>Available Users:</small><ul class='list-unstyled text-white-50 small'>";
        while($u = mysqli_fetch_assoc($users)) {
            echo "<li>" . $u['email'] . "</li>";
        }
        echo "</ul>";
    }
    ?>
</div>

</body>
</html>
