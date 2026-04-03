<?php
require_once "auth_check.php";
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $membership = mysqli_real_escape_string($conn, $_POST['membership']);

    // New Fields
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $emergency_contact = mysqli_real_escape_string($conn, $_POST['emergency_contact']);

    $sql = "INSERT INTO members (name, email, phone, membership, gender, dob, blood_group, address, emergency_contact)
            VALUES ('$name', '$email', '$phone', '$membership', '$gender', '$dob', '$blood_group', '$address', '$emergency_contact')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../index.php?success=1");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "Invalid request ❌";
}
?>
