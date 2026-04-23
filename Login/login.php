<?php
session_start();
$rememberedEmail = ''; // Cleared since "Remember Me" is removed

// Check if there's a redirect parameter in the URL (e.g., from products.php)
$redirectUrl = isset($_GET['redirect']) ? $_GET['redirect'] : 'products.php';  // Default to 'products.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Mimi Glow</title>
  <link rel="stylesheet" href="login_style.css">
</head>
<body>
<div class="split-container">
  <div class="left-side"></div> <!-- Image only -->

  <div class="right-side">
    <div class="login-form">
      <h2>Welcome Back to Mimi Glow</h2>
      <form action="validate_login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($rememberedEmail) ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <div>
          <a href="forgot_password.php">Forgot Password?</a>
        </div>

        <div style="text-align: center;">
          <button type="submit" class="login-btn">Login</button>
        </div>
      </form>
      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>
</div>
</body>
</html>
