<?php
session_start();
include("../db_connection.php");

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (empty($email) || empty($token)) {
    die("Invalid request.");
}

// Check if token matches and is not expired
$stmt = $conn->prepare("SELECT reset_token_expiry FROM users WHERE email = ? AND reset_token = ?");
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Invalid or expired reset link.");
}

$row = $result->fetch_assoc();
if (strtotime($row['reset_token_expiry']) < time()) {
    die("Reset link expired. Please request a new one.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - Mimi Glow</title>
  <link rel="stylesheet" href="login_style.css">
</head>
<body>
<div class="split-container">
  <div class="left-side"></div>
  <div class="right-side">
    <div class="login-form">
      <h2>Create New Password</h2>
      <form action="update_password.php" method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        
        <label>New Password:</label>
        <input type="password" name="new_password" required>
        
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="login-btn">Update Password</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
