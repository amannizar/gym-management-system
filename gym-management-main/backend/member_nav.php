<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit;
}
$member_name = $_SESSION['member_name'];
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-nav">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="member_dashboard.php">
            <span style="font-size: 1.5rem;">💪</span>
            <span class="fw-bold tracking-wide">GYM<span class="text-primary">PRO</span></span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#premiumNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Items -->
        <div class="collapse navbar-collapse" id="premiumNav">
            <ul class="navbar-nav ms-auto gap-1 align-items-center">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'member_dashboard.php' ? 'active' : '' ?>" href="member_dashboard.php">
                        Dashboard
                    </a>
                </li>

                <!-- Nutrition Dropdown (Combined) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($current_page, ['diet.php', 'diet_store.php', 'diet_consult.php', 'premium_diet.php']) ? 'active' : '' ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        Nutrition
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" style="background: rgba(20,20,20,0.95); backdrop-filter: blur(10px);">
                         <li><a class="dropdown-item d-flex align-items-center gap-2" href="diet.php"><span>🥗</span> My Diet Plan</a></li>
                         <li><a class="dropdown-item d-flex align-items-center gap-2" href="diet_store.php"><span>🥡</span> Order Food</a></li>
                         <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                         <li><a class="dropdown-item d-flex align-items-center gap-2" href="diet_consult.php"><span>📸</span> Scan Meal</a></li>
                         <li><a class="dropdown-item d-flex align-items-center gap-2 text-warning fw-bold" href="premium_diet.php"><span>💎</span> Premium Plan</a></li>
                    </ul>
                </li>

                <!-- Health Suite (Tracking Only) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($current_page, ['sleep_monitor.php', 'water_tracker.php', 'iot.php']) ? 'active' : '' ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        Health Suite
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" style="background: rgba(20,20,20,0.95); backdrop-filter: blur(10px);">
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="sleep_monitor.php"><span>🛌</span> Sleep Monitor</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="water_tracker.php"><span>💧</span> Water Tracker</a></li>
                        <li><a class="dropdown-item d-flex align-items-center gap-2" href="iot.php"><span>⌚</span> IoT Devices</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'reports.php' ? 'active' : '' ?>" href="reports.php">Reports</a>
                </li>

                <!-- Gym Info Removed -->
                <!-- Profile & User -->
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="avatar-circle"><?= substr($member_name, 0, 1) ?></div>
                        <span class="d-lg-none d-xl-inline"><?= htmlspecialchars($member_name) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-0 shadow-lg" style="background: rgba(20,20,20,0.95);">
                        <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                        <li><a class="dropdown-item" href="subscription.php">Membership</a></li>
                        <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                        <li><a class="dropdown-item text-danger" href="logout_feedback.php">Logout</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- Offset for fixed header -->
<div style="margin-top: 100px;"></div>

<!-- AI Widget -->
<div class="ai-widget">
    <div class="ai-bubble" id="aiMsg">Mars is online 🪐</div>
    <a href="ai_coach.php" class="ai-button">🤖</a>
</div>

<script>
    setTimeout(() => {
        document.getElementById('aiMsg').classList.add('show');
    }, 3000); // 3 seconds delay
</script>
