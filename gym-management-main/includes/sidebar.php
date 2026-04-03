<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fa-solid fa-dumbbell"></i>
            <span>IRON FORGE</span>
        </div>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="menu-header">Members</li>
        <li>
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-plus"></i>
                <span>Register Member</span>
            </a>
        </li>
        <li>
            <a href="view_members.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_members.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                <span>All Members</span>
            </a>
        </li>
        
        <li class="menu-header">Management</li>
        <li>
            <a href="manage_trainers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_trainers.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-ninja"></i>
                <span>Trainers</span>
            </a>
        </li>
        <li>
            <a href="equipment.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'equipment.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-weight-hanging"></i>
                <span>Equipment</span>
            </a>
        </li>
        <li>
            <a href="admin_attendance.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_attendance.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-clipboard-user"></i>
                <span>Attendance</span>
            </a>
        </li>
        <li>
            <a href="admin_announcements.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_announcements.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Announcements</span>
            </a>
        </li>
        
        <li class="menu-header">Reports</li>
        <li>
            <a href="admin_reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_reports.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i>
                <span>Analytics</span>
            </a>
        </li>
        
        <li class="menu-header">General</li>
        <li>
            <a href="gym_profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gym_profile.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-circle-info"></i>
                <span>Gym Info</span>
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</div>
