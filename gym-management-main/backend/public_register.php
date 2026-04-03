<?php
// No Auth Check
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $membership = mysqli_real_escape_string($conn, $_POST['membership']);
    
    // Password Handling
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Extended Fields
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $emergency_contact = mysqli_real_escape_string($conn, $_POST['emergency_contact']);

    $sql = "INSERT INTO members (name, email, phone, membership, password, gender, dob, blood_group, address, emergency_contact)
            VALUES ('$name', '$email', '$phone', '$membership', '$hashed_password', '$gender', '$dob', '$blood_group', '$address', '$emergency_contact')";

    if (mysqli_query($conn, $sql)) {
        // success=2 code for "Please login now" message
        header("Location: ../member_login.php?success=2");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "Invalid request ❌";
}
?>
