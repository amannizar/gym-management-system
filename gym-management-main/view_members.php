<?php
require_once "backend/auth_check.php";
require_once __DIR__ . "/backend/db.php";
require_once "includes/header.php";

/* Search logic */
$search = $_GET['search'] ?? '';

if ($search != '') {
    $sql = "SELECT * FROM members 
            WHERE name LIKE '%$search%' 
               OR email LIKE '%$search%' 
               OR phone LIKE '%$search%'
            ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM members ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header d-flex justify-content-between align-items-center">
            <h1 class="page_title">Member Directory</h1>
            <a href="index.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New</a>
        </div>

        <div class="card">
            <div class="card-header">
                <span>All Members List</span>
                <form method="GET" class="d-flex gap-2" style="max-width: 400px;">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <a href="view_members.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-refresh"></i></a>
                </form>
            </div>

            <div class="card-body p-0">
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success m-3">
                        Member deleted successfully 🗑️
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Membership</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>#<?= $row['id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle" style="width: 30px; height: 30px; background: #333; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">
                                                    <?= strtoupper(substr($row['name'], 0, 1)) ?>
                                                </div>
                                                <?= $row['name'] ?>
                                            </div>
                                        </td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['phone'] ?></td>
                                        <td>
                                            <?php 
                                            $badgeClass = 'bg-secondary';
                                            if($row['membership'] == 'VIP') $badgeClass = 'bg-warning text-dark';
                                            if($row['membership'] == 'Yearly') $badgeClass = 'bg-info text-dark';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $row['membership'] ?></span>
                                        </td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td class="text-end">
                                            <a href="admin_member_details.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-action-view" title="View"><i class="fa-regular fa-eye"></i></a>
                                            <a href="edit_member.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-action-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                            <a href="delete_member.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-action-delete" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-solid fa-ghost fa-2x mb-3"></i><br>
                                            No members found matching your search.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted small text-center">
                Showing all registered members
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
