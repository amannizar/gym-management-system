<?php
require_once "backend/auth_check.php";
require_once "backend/setup_admin_tables.php";
require_once "backend/db.php";
require_once "includes/header.php";

$pdo = getDB();
$success = "";

// Handle Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO announcements (message) VALUES (?)");
        $stmt->execute([$message]);
        $success = "Announcement broadcasted successfully!";
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    echo "<script>window.location.href='admin_announcements.php';</script>";
    exit;
}

$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header">
            <h1 class="page_title">Global Announcements</h1>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">Compose New</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Message Content</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Type message for all members..." required></textarea>
                                <div class="form-text text-muted mt-2">This will appear on member dashboards immediately.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-paper-plane"></i> Broadcast</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <h4 class="mb-3 text-muted" style="font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;">Announcement History</h4>
                
                <?php foreach ($announcements as $ann): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted"><i class="fa-regular fa-clock"></i> <?= date('M d, Y h:i A', strtotime($ann['created_at'])) ?></small>
                            <a href="?delete_id=<?= $ann['id'] ?>" class="text-danger small text-decoration-none" onclick="return confirm('Delete this announcement?')"><i class="fa-solid fa-times"></i></a>
                        </div>
                        <p class="card-text fs-5 mb-0"><?= nl2br(htmlspecialchars($ann['message'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($announcements)): ?>
                    <div class="text-center text-muted py-5 card">
                        <p class="mb-0">No announcements found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
