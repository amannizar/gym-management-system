<?php
session_start();
if (isset($_SESSION['member_id'])) {
    header("Location: member_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Member - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-card {
            max-width: 400px;
            margin: auto;
        }
        .login-logo {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Background Slideshow -->
<ul class="slideshow">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>
<div class="slideshow-overlay"></div>

<div class="page" style="position: relative; z-index: 3;">
    <div class="card login-card">
        <div class="card-header">Member Portal</div>
        
        <div class="card-body">
            <div class="login-logo">🏃</div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">Invalid email or password!</div>
            <?php endif; ?>

            <form method="POST" action="backend/auth_member.php">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter password (default: member123)">
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-bold" style="padding: 12px;">LOGIN AS MEMBER</button>
                    <div class="text-center my-2 text-white-50">- OR -</div>
                    <a href="login.php" class="btn btn-outline-warning fw-bold">Login as Admin</a>
                    <a href="signup.php" class="btn btn-link text-white-50 text-decoration-none btn-sm">New Member? Register Here</a>
                    <a href="index.php" class="btn btn-link text-white-50 text-decoration-none btn-sm">← Back to Home</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
