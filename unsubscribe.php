<?php
include 'config.php';

header('Content-Type: application/json'); // Make sure it returns JSON

if (isset($_GET['token'])) {
    $token = $conn->real_escape_string($_GET['token']);

    $sql = "UPDATE subscribers SET is_active = 0 WHERE token='$token' AND is_active=1";

    if ($conn->query($sql) && $conn->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "You have been unsubscribed!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid unsubscribe link or already unsubscribed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No token provided."]);
}

$conn->close();
?>
