
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mimi Glow</title>
    <link rel="stylesheet" href="login_style.css">
    <style>
        input:invalid {
            border-color: red;
        }
        input:valid {
            border-color:green;
        }
    </style>
</head>
<body>
<div class="split-container">
  <div class="left-side"></div>
  <div class="right-side">
    <div class="login-form">
      <h2>Create Your Mimi Glow Account</h2>
        <form action="validate_registration.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" 
                pattern="[A-Za-z\s]{6,}" 
                title="Only alphabets, at least 6 characters" >
                <!-- required placeholder="e.g. Sora Tamang" -->

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" 
                pattern="(9[6-8][0-9])\d{7}" 
                title="Enter a valid Nepali phone number"> 
                <!-- required placeholder="e.g. 9801234567" -->

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                pattern="^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@gmail\.com$" 
                title="Must be a valid Gmail address">
                <!-- required placeholder="e.g. glowlover123@gmail.com"> -->

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" 
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
                title="Min 8 chars, 1 uppercase, 1 number, 1 special char"> 
                <!-- required placeholder="Choose a strong password"> -->

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" 
                minlength="8"> 

            <button type="submit" class="login-btn">Register</button>
        </form>
        <p>Already a member? <a href="login.php">Login here</a></p>
    </div>
    </div>
</div>
</body>
</html>
