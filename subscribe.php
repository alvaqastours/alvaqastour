<?php
header('Content-Type: application/json'); // ensure JSON output
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "❌ Invalid email address."
        ]);
        exit;
    }

    // generate token
    $token = bin2hex(random_bytes(16));

    // insert subscriber
    $stmt = $conn->prepare("INSERT INTO subscribers (email, token, is_active) VALUES (?, ?, 1)");
    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "⚠️ Database error: " . $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("ss", $email, $token);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "✅ Subscription successful!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "⚠️ Already subscribed or database error."
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "⚠️ Invalid request method."
    ]);
}
?>
