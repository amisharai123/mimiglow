<?php
session_start();
include("../db_connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes manually (since no Composer)
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = strtolower(trim($_POST['email']));

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Save token in DB
        $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_token_expiry=? WHERE email=?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Build reset link
        $resetLink = "http://localhost/Mimi_Glow/Login/reset_password.php?email=" . urlencode($email) . "&token=" . urlencode($token);

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'Your_Email_Here';        // your Gmail
            $mail->Password   = 'Your_Password_Here';          // your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your_project_email@gmail.com', 'Mimi Glow');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password - Mimi Glow';
            $mail->Body    = "
                <p>Hi,</p>
                <p>We received a request to reset your password. Click below to reset:</p>
                <a href='$resetLink'>$resetLink</a>
                <p>This link is valid for 15 minutes.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
            exit();
        }
    }

    // Show same message regardless of whether email exists
    echo "<script>
        alert('A reset link has been sent to your Gmail address if it exists in our system.');
        window.location.href = 'login.php';
    </script>";
    exit();
} else {
    header("Location: forgot_password.php");
    exit();
}
?>
