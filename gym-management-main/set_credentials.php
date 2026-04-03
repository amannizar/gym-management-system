<?php
require_once __DIR__ . "/backend/db.php";

// Set Admin Credentials
$admin_user = 'admin';
$admin_pass = 'admin';
$admin_hash = password_hash($admin_pass, PASSWORD_DEFAULT);

$check_admin = mysqli_query($conn, "SELECT id FROM admins WHERE username='$admin_user'");
if (mysqli_num_rows($check_admin) > 0) {
    mysqli_query($conn, "UPDATE admins SET password='$admin_hash' WHERE username='$admin_user'");
    echo "Admin password updated.\n";
} else {
    mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$admin_user', '$admin_hash')");
    echo "Admin user created.\n";
}

// Set Member Credentials
$member_name = 'Demo Member';
$member_email = 'member@example.com';
$member_pass = 'member';
$member_hash = password_hash($member_pass, PASSWORD_DEFAULT);

$check_member = mysqli_query($conn, "SELECT id FROM members WHERE email='$member_email'");
if (mysqli_num_rows($check_member) > 0) {
    mysqli_query($conn, "UPDATE members SET password='$member_hash' WHERE email='$member_email'");
    echo "Member password updated.\n";
} else {
    mysqli_query($conn, "INSERT INTO members (name, email, password) VALUES ('$member_name', '$member_email', '$member_hash')");
    echo "Member user created.\n";
}

?>
