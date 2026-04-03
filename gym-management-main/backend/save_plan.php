<?php
require_once "db.php";
session_start();

if (!isset($_SESSION['member_id'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['plan'])) {
    $plan = mysqli_real_escape_string($conn, $data['plan']);
    $member_id = $_SESSION['member_id'];
    
    $sql = "INSERT INTO member_plans (member_id, plan_content) VALUES ($member_id, '$plan')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No plan data"]);
}
?>
