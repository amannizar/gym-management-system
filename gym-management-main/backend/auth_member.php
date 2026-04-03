<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, name, password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Login Success
            $_SESSION['member_id'] = $row['id'];
            $_SESSION['member_name'] = $row['name'];
            header("Location: ../member_dashboard.php");
            exit;
        }
    }

    // Login Failed
    header("Location: ../member_login.php?error=1");
    exit;
}
?>
