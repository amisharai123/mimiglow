<?php
session_start();
include("../db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($email) || empty($token) || empty($newPassword) || empty($confirmPassword)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // Password strength check (minimum 8 characters, upper, lower, number, special char)
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
        echo "<script>alert('Password must be at least 8 characters long, contain uppercase, lowercase, number, and special character.'); window.history.back();</script>";
        exit;
    }

    // Now verify the token and expiry
   $stmt = $conn->prepare("SELECT reset_token_expiry FROM users WHERE email=? AND reset_token=? AND reset_token IS NOT NULL");

    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $expiry = $row['reset_token_expiry'];

        if (strtotime($expiry) < time()) {
            echo "<script>alert('Reset link has expired. Please request a new one.'); window.location.href = 'forgot_password.php';</script>";
            exit;
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password and clear the token
        $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE email=?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        echo "<script>alert('Password updated successfully! Please login.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Invalid or expired reset link.'); window.location.href = 'forgot_password.php';</script>";
    }
}
?>
