<?php
require_once "backend/auth_check.php";
require_once __DIR__ . "/backend/db.php";
require_once "includes/header.php";
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header">
            <h1 class="page_title">Register New Member</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <span>Member Details Form</span>
                        <a href="dashboard.php" class="btn btn-secondary btn-sm"> <i class="fa-solid fa-arrow-left"></i> Back</a>
                    </div>
                    
                    <div class="card-body">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">Member added successfully ✅</div>
                        <?php endif; ?>

                        <form method="POST" action="backend/register.php">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter full name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required placeholder="Enter email address">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" required placeholder="Enter phone number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Emergency Contact</label>
                                    <input type="text" name="emergency_contact" class="form-control" placeholder="Name & Number">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <input type="text" name="blood_group" class="form-control" placeholder="O+">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Address</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Street, City, Zip"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Membership Type</label>
                                <select name="membership" class="form-select" required>
                                    <option value="">Select Membership Plan</option>
                                    <option>Monthly</option>
                                    <option>Yearly</option>
                                    <option>VIP</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-primary px-4"><i class="fa-solid fa-check"></i> Register Member</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Quick Tips</div>
                    <div class="card-body">
                        <ul class="text-muted small ps-3">
                            <li class="mb-2">Ensure email is unique for each member.</li>
                            <li class="mb-2">Emergency contact is crucial for safety.</li>
                            <li class="mb-2">VIP members get access to the spa and personal trainer.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
