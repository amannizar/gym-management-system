<?php
require_once "backend/auth_check.php";
require_once __DIR__ . "/backend/db.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid Request";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Member not found";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management - Edit Member</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">
    <div class="card">
        <div class="card-header">Edit Member</div>
        
        <div class="card-body">
            <form method="POST" action="update_member.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Membership Type</label>
                    <select name="membership" class="form-select" required>
                        <option value="Monthly" <?= $row['membership'] == 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                        <option value="Yearly" <?= $row['membership'] == 'Yearly' ? 'selected' : '' ?>>Yearly</option>
                        <option value="VIP" <?= $row['membership'] == 'VIP' ? 'selected' : '' ?>>VIP</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Update Member</button>
                    <a href="view_members.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
