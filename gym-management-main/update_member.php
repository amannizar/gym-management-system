<?php
require_once "backend/auth_check.php";
require_once __DIR__ . "/backend/db.php";

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$membership = $_POST['membership'];

// TODO: Use prepared statements here too like in edit_member.php
$sql = "UPDATE members 
        SET name='$name', email='$email', phone='$phone', membership='$membership'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: view_members.php?updated=1");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
