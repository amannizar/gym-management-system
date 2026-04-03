<?php
require_once "backend/auth_check.php";
require_once "backend/setup_admin_tables.php"; 
require_once "backend/db.php";
require_once "includes/header.php";

$pdo = getDB();
$message = "";

// Handle Add Trainer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_trainer'])) {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $bio = $_POST['bio'];
    $photo_url = !empty($_POST['photo_url']) ? $_POST['photo_url'] : 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=200&fit=crop';

    $stmt = $pdo->prepare("INSERT INTO trainers (name, specialty, bio, photo_url) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $specialty, $bio, $photo_url])) {
        $message = "Trainer added successfully!";
    } else {
        $message = "Error adding trainer.";
    }
}

// Handle Delete Trainer
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM trainers WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "Trainer removed.";
    }
}

// Fetch Trainers
$stmt = $pdo->query("SELECT * FROM trainers ORDER BY created_at DESC");
$trainers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header d-flex justify-content-between align-items-center">
            <h1 class="page_title">Manage Trainers</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainerModal"><i class="fa-solid fa-plus"></i> Add Trainer</button>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">Trainer Staff List</div>
            <div class="card-body p-0">
                <div class="table-responsive" style="border: none;">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="padding-left: 20px;">Photo</th>
                                <th>Name</th>
                                <th>Specialty</th>
                                <th>Bio</th>
                                <th class="text-end" style="padding-right: 20px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trainers as $trainer): ?>
                            <tr>
                                <td style="padding-left: 20px;">
                                    <img src="<?= htmlspecialchars($trainer['photo_url']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; box-shadow: 0 2px 10px rgba(0,0,0,0.2);" alt="Trainer">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($trainer['name']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($trainer['specialty']) ?></span></td>
                                <td><small class="text-muted"><?= htmlspecialchars(substr($trainer['bio'], 0, 50)) . (strlen($trainer['bio']) > 50 ? '...' : '') ?></small></td>
                                <td class="text-end" style="padding-right: 20px;">
                                    <a href="?delete_id=<?= $trainer['id'] ?>" class="btn btn-sm btn-action-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i> Remove</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($trainers)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-5">No trainers found. Click "+ Add Trainer" to start.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Trainer Modal -->
<div class="modal fade" id="addTrainerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-color);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title">Add New Trainer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specialty</label>
                        <input type="text" name="specialty" class="form-control" placeholder="e.g. Yoga, Strength, Cardio" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo URL (Optional)</label>
                        <input type="url" name="photo_url" class="form-control" placeholder="https://example.com/photo.jpg">
                        <div class="form-text text-muted" style="color: #666 !important;">Leave empty for a random fitness image.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_trainer" class="btn btn-primary">Save Trainer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
