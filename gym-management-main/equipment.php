<?php
require_once "backend/auth_check.php";
require_once "backend/setup_admin_tables.php";
require_once "backend/db.php";
require_once "includes/header.php";

$pdo = getDB();

// Handle Status Change
if (isset($_GET['id']) && isset($_GET['status'])) {
    $stmt = $pdo->prepare("UPDATE equipment SET status = ?, last_maintenance = NOW() WHERE id = ?");
    $stmt->execute([$_GET['status'], $_GET['id']]);
    echo "<script>window.location.href='equipment.php';</script>";
    exit;
}

// Handle Add Equipment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_eq'])) {
    $name = $_POST['name'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    
    $stmt = $pdo->prepare("INSERT INTO equipment (name, status, notes, last_maintenance) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $status, $notes]);
    echo "<script>window.location.href='equipment.php';</script>";
    exit;
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM equipment WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    echo "<script>window.location.href='equipment.php';</script>";
    exit;
}

$equipment_list = $pdo->query("SELECT * FROM equipment ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

function getStatusColor($status) {
    return match($status) {
        'Active' => 'success',
        'Maintenance' => 'warning',
        'Broken' => 'danger',
        default => 'secondary'
    };
}
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header d-flex justify-content-between align-items-center">
            <h1 class="page_title">Equipment Tracker</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEqModal"><i class="fa-solid fa-plus"></i> Add Equipment</button>
        </div>
        
        <div class="row">
            <?php foreach ($equipment_list as $eq): 
                $color = getStatusColor($eq['status']);
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0 text-white"><?= htmlspecialchars($eq['name']) ?></h5>
                        <span class="badge bg-<?= $color ?>"><?= htmlspecialchars($eq['status']) ?></span>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted small mb-2">Notes: <?= htmlspecialchars($eq['notes'] ?: 'No notes') ?></p>
                        <p class="card-text mb-3"><small class="text-muted"><i class="fa-regular fa-clock"></i> Last Maint: <?= htmlspecialchars($eq['last_maintenance']) ?></small></p>
                        
                        <div class="d-flex gap-2 mb-3">
                            <a href="?id=<?= $eq['id'] ?>&status=Active" class="btn btn-sm btn-outline-success flex-fill <?= $eq['status'] == 'Active' ? 'active' : '' ?>">Active</a>
                            <a href="?id=<?= $eq['id'] ?>&status=Maintenance" class="btn btn-sm btn-outline-warning flex-fill <?= $eq['status'] == 'Maintenance' ? 'active' : '' ?>">Maint</a>
                            <a href="?id=<?= $eq['id'] ?>&status=Broken" class="btn btn-sm btn-outline-danger flex-fill <?= $eq['status'] == 'Broken' ? 'active' : '' ?>">Broken</a>
                        </div>
                        
                        <div class="text-end border-top pt-2 mt-2" style="border-color: rgba(255,255,255,0.05) !important;">
                             <a href="?delete_id=<?= $eq['id'] ?>" class="text-danger small text-decoration-none" onclick="return confirm('Delete this item?')"><i class="fa-solid fa-trash"></i> Remove Item</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($equipment_list)): ?>
            <div class="col-12 text-center py-5 text-muted">
                <h4>No equipment tracked yet.</h4>
                <p>Click "+ Add Equipment" to start inventory.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addEqModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-color);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title">Add Equipment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Equipment Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Status</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Broken">Broken</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_eq" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
