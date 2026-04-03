<?php
require_once "backend/auth_check.php";
require_once "backend/db.php";

// Fetch Stats
$total_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members"))['count'];
$monthly_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members WHERE membership='Monthly'"))['count'];
$yearly_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members WHERE membership='Yearly'"))['count'];
$vip_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members WHERE membership='VIP'"))['count'];

// Revenue Calculation
$revenue = ($monthly_members * 1500) + ($yearly_members * 12000) + ($vip_members * 15000);

require_once "includes/header.php";
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <!-- Background Quote -->
        <div id="bg-quote" class="background-quote">NO PAIN<br>NO GAIN</div>
        
        <script>
            const quotes = [
                "NO PAIN<br>NO GAIN",
                "TRAIN<br>INSANE",
                "JUST<br>DO IT",
                "BEAST<br>MODE",
                "LIGHT<br>WEIGHT",
                "STAY<br>HARD"
            ];
            let quoteIdx = 0;
            setInterval(() => {
                const el = document.getElementById('bg-quote');
                el.style.opacity = 0;
                setTimeout(() => {
                    quoteIdx = (quoteIdx + 1) % quotes.length;
                    el.innerHTML = quotes[quoteIdx];
                    el.style.opacity = 1;
                }, 2000);
            }, 10000);
        </script>
        
        <div class="page_header">
            <h1 class="page_title">Dashboard Overview</h1>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_members ?></div>
                    <div class="stat-label">Total Members</div>
                    <i class="fa-solid fa-users stat-icon"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-number">₹<?= number_format($revenue) ?></div>
                    <div class="stat-label">Est. Revenue</div>
                    <i class="fa-solid fa-indian-rupee-sign stat-icon"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $vip_members ?></div>
                    <div class="stat-label">VIP Members</div>
                    <i class="fa-solid fa-crown stat-icon"></i>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $monthly_members + $yearly_members ?></div>
                    <div class="stat-label">Regular Members</div>
                    <i class="fa-solid fa-dumbbell stat-icon"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions (Previously Navigation) -->
        <div class="row g-4">
            <div class="col-12">
                 <h4 class="mb-3 text-white">Quick Actions</h4>
            </div>
            
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php" class="card h-100 text-center p-4 text-decoration-none card-hover" style="display:block; transition: all 0.2s;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">📝</div>
                    <h5 style="font-size: 1rem; color: var(--text-color);">Register</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="view_members.php" class="card h-100 text-center p-4 text-decoration-none card-hover" style="display:block; transition: all 0.2s;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">👥</div>
                    <h5 style="font-size: 1rem; color: var(--text-color);">Members</h5>
                </a>
            </div>
             <div class="col-6 col-md-4 col-lg-2">
                <a href="manage_trainers.php" class="card h-100 text-center p-4 text-decoration-none card-hover" style="display:block; transition: all 0.2s;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">🧘‍♂️</div>
                    <h5 style="font-size: 1rem; color: var(--text-color);">Trainers</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="equipment.php" class="card h-100 text-center p-4 text-decoration-none card-hover" style="display:block; transition: all 0.2s;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">🏋️‍♂️</div>
                    <h5 style="font-size: 1rem; color: var(--text-color);">Equipment</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="admin_attendance.php" class="card h-100 text-center p-4 text-decoration-none card-hover" style="display:block; transition: all 0.2s;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">📋</div>
                    <h5 style="font-size: 1rem; color: var(--text-color);">Attendance</h5>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="admin_announcements.php" class="card h-100 text-center p-4 text-decoration-none card-hover" style="display:block; transition: all 0.2s;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">📢</div>
                    <h5 style="font-size: 1rem; color: var(--text-color);">Notify</h5>
                </a>
            </div>
        </div>

    </div>
</div>

<?php require_once "includes/footer.php"; ?>
