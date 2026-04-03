<?php
// No Auth Check - Public Page
require_once "backend/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management - Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .register-card { max-width: 600px; margin: 30px auto; }
    </style>
</head>
<body>

<div class="page">
    <div class="card register-card">
        <div class="card-header bg-success text-white">
            New Member Registration 📝
        </div>
        
        <div class="card-body">
            <form method="POST" action="backend/public_register.php">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Enter full name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter email address">
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required placeholder="Enter phone number">
                </div>

                <div class="mb-3">
                    <label class="form-label">Create Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Create a strong password">
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Street, City, Zip"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Blood Group</label>
                        <input type="text" name="blood_group" class="form-control" placeholder="O+">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-control" placeholder="Name & Number">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Membership Type</label>
                    <select name="membership" class="form-select" required>
                        <option value="">Select Membership</option>
                        <option>Monthly</option>
                        <option>Yearly</option>
                        <option>VIP</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-success">Register Now</button>
                    <a href="member_login.php" class="btn btn-outline-secondary">Already a member? Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
