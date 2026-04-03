<?php
require_once "backend/auth_check.php";
require_once __DIR__ . "/backend/db.php";

if (!isset($_GET['id'])) {
    header("Location: view_members.php");
    exit;
}

$id = intval($_GET['id']); // safety

$sql = "DELETE FROM members WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: view_members.php?deleted=1");
    exit;
} else {
    echo "Error deleting member: " . mysqli_error($conn);
}
?>
