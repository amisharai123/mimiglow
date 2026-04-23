<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - Mimi Glow</title>
  <link rel="stylesheet" href="login_style.css">
</head>
<body>
<div class="split-container">
  <div class="left-side"></div>
  <div class="right-side">
    <div class="login-form">
      <h2>Forgot Your Password?</h2>
    <form action="send_reset_link.php" method="POST">
      <label for="email">Enter your Gmail address:</label>
      <input type="email" id="email" name="email" required pattern="^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@gmail\.com$">
      <button type="submit" class="login-btn">Send Reset Link</button>
    </form>
    <p><a href="login.php">Back to Login</a></p>
  </div>
</div>
</div>
</body>
</html>
