<?php
session_start();
require_once "backend/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch feedback joined with members
$query = "SELECT f.*, m.name, m.email 
          FROM feedback f 
          JOIN members m ON f.member_id = m.id 
          ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $query);

// Delete functionality
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM feedback WHERE id=$id");
    header("Location: admin_feedback.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .star-filled { color: #ffc107; }
        .star-empty { color: #444; }
        .feedback-card {
            background: rgba(20,20,20,0.6);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-dark text-white">

<?php include "backend/admin_nav.php"; ?>

<div class="container mt-4">
    <h2 class="mb-4 text-primary">Member Feedback & Reviews</h2>

    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 mb-4">
                    <div class="feedback-card h-100 position-relative">
                        <a href="admin_feedback.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-3" onclick="return confirm('Delete this review?')">
                            <i class="fas fa-trash"></i>
                        </a>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2 pe-5">
                            <h5 class="mb-0 text-white"><?= htmlspecialchars($row['name']) ?></h5>
                            <small class="text-muted"><?= date('M j, Y g:i A', strtotime($row['created_at'])) ?></small>
                        </div>
                        <div class="mb-3">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $row['rating'] ? 'star-filled' : 'star-empty' ?>"></i>
                            <?php endfor; ?>
                            <span class="ms-2 text-white-50">(<?= $row['rating'] ?>/5)</span>
                        </div>
                        
                        <div class="p-3 bg-dark rounded border border-secondary text-white-50 fst-italic">
                            "<?= htmlspecialchars($row['message'] ?: 'No message provided.') ?>"
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-secondary bg-dark border-secondary text-muted">
                    No feedback has been submitted yet.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
