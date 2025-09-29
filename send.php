<?php
require 'config.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = trim($_POST['message']);
    $filePath = "";

    // handle file upload
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filePath = $uploadDir . basename($_FILES['attachment']['name']);
        move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath);
    }

    // fetch all active subscribers
    $result = $conn->query("SELECT email, token FROM subscribers WHERE is_active = 1");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mail = new PHPMailer(true);
            try {
                // ✅ Use PHP mail() instead of SMTP (InfinityFree compatible)
                $mail->isMail();

                $mail->setFrom("no-reply@shaikhfaizan.ct.ws", "Alvaqastours");
                $mail->addAddress($row['email']);

                if ($filePath) {
                    $mail->addAttachment($filePath);
                }

                // unsubscribe link
                $unsubscribeLink = "https://shaikhfaizan.ct.ws/newsletter/unsubscribe.php?token=" . $row['token'];

                $mail->isHTML(true);
                $mail->Subject = "Newsletter from Alvaqastours";
                $mail->Body    = nl2br(htmlspecialchars($message)) .
                                "<br><br><a href='$unsubscribeLink'>Unsubscribe</a>";

                $mail->send();
            } catch (Exception $e) {
                echo "❌ Could not send to {$row['email']}. Error: {$mail->ErrorInfo}<br>";
            }
        }
        echo "✅ Emails sent successfully!";
    } else {
        echo "⚠️ No active subscribers found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send Newsletter</title>
</head>
<body>
  <h2>Send Newsletter</h2>
  <form action="send.php" method="POST" enctype="multipart/form-data">
    <textarea name="message" rows="6" cols="50" placeholder="Write your message here..." required></textarea><br><br>
    <input type="file" name="attachment"><br><br>
    <button type="submit">Send to Subscribers</button>
  </form>
</body>
</html>
