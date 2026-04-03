<?php
require_once "backend/auth_check.php";
require_once "backend/setup_admin_tables.php";
require_once "backend/db.php";
require_once "includes/header.php";

$pdo = getDB();
$today = date('Y-m-d');
$message = "";

// Handle Mark Attendance (Check-in)
if (isset($_GET['checkin'])) {
    $mid = $_GET['checkin'];
    
    // Check if already checked in
    $check = $pdo->prepare("SELECT id FROM attendance WHERE member_id = ? AND date = ?");
    $check->execute([$mid, $today]);
    
    if (!$check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO attendance (member_id, date, status, check_in_time) VALUES (?, ?, 'Present', NOW())");
        $stmt->execute([$mid, $today]);
        echo "<script>window.location.href='admin_attendance.php?msg=checked_in';</script>";
        exit;
    } else {
         echo "<script>window.location.href='admin_attendance.php?msg=already_checked_in';</script>";
         exit;
    }
}

// Handle Check-out
if (isset($_GET['checkout'])) {
    $mid = $_GET['checkout'];
    $stmt = $pdo->prepare("UPDATE attendance SET check_out_time = NOW() WHERE member_id = ? AND date = ? AND check_out_time IS NULL");
    $stmt->execute([$mid, $today]);
    echo "<script>window.location.href='admin_attendance.php?msg=checked_out';</script>";
    exit;
}

// Messages handling
if(isset($_GET['msg'])) {
    if($_GET['msg'] == 'checked_in') $message = "Member checked in successfully.";
    if($_GET['msg'] == 'already_checked_in') $message = "Member is already marked present today.";
    if($_GET['msg'] == 'checked_out') $message = "Member checked out successfully.";
}

// Fetch Today's Attendance
$logs = $pdo->prepare("
    SELECT a.*, m.name, m.email 
    FROM attendance a 
    JOIN members m ON a.member_id = m.id 
    WHERE a.date = ? 
    ORDER BY a.check_in_time DESC
");
$logs->execute([$today]);
$attendance_list = $logs->fetchAll(PDO::FETCH_ASSOC);

// Fetch All Members (for manual marking)
$members = $pdo->query("SELECT id, name, email FROM members ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page_title">Daily Attendance</h1>
                <p class="text-muted mb-0"><?= date('l, F j, Y') ?></p>
            </div>
            <div>
                <button class="btn btn-secondary"><i class="fa-solid fa-calendar"></i> History</button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Mark Attendance Column -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">Mark Attendance</div>
                    <div class="card-body d-flex flex-column">
                        <input type="text" id="searchMember" class="form-control mb-3" placeholder="Search member name...">
                        
                        <div class="list-group overflow-auto flex-grow-1" style="max-height: 500px; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                            <?php foreach ($members as $m): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center member-item" style="background: var(--input-bg); border-color: var(--border-color); color: var(--text-color);">
                                    <div>
                                        <strong><?= htmlspecialchars($m['name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($m['email']) ?></small>
                                    </div>
                                    <a href="?checkin=<?= $m['id'] ?>" class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Log Column -->
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">
                        <span>Today's Log</span>
                        <span class="badge bg-primary"><?= count($attendance_list) ?> Present</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="border: none;">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="padding-left: 20px;">Name</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Status</th>
                                        <th class="text-end" style="padding-right: 20px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance_list as $log): ?>
                                        <tr>
                                            <td style="padding-left: 20px; font-weight: 500;"><?= htmlspecialchars($log['name']) ?></td>
                                            <td><?= date('h:i A', strtotime($log['check_in_time'])) ?></td>
                                            <td>
                                                <?= $log['check_out_time'] ? date('h:i A', strtotime($log['check_out_time'])) : '<span class="text-muted">--</span>' ?>
                                            </td>
                                            <td><span class="badge bg-success">Present</span></td>
                                            <td class="text-end" style="padding-right: 20px;">
                                                <?php if (!$log['check_out_time']): ?>
                                                    <a href="?checkout=<?= $log['member_id'] ?>" class="btn btn-sm btn-outline-warning">Check Out</a>
                                                <?php else: ?>
                                                    <span class="text-muted small"><i class="fa-solid fa-check-double"></i> Completed</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($attendance_list)): ?>
                                        <tr><td colspan="5" class="text-center text-muted py-5">No attendance marked yet today.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Client-side Search
    document.getElementById('searchMember').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let items = document.querySelectorAll('.member-item');
        items.forEach(item => {
            let text = item.innerText.toLowerCase();
            item.style.display = text.includes(val) ? 'flex' : 'none';
        });
    });
</script>

<?php require_once "includes/footer.php"; ?>
