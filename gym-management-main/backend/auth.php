<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Login Success
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $username;
            header("Location: ../dashboard.php");
            exit;
        }
    }

    // Login Failed
    header("Location: ../login.php?error=1");
    exit;
}
?>
