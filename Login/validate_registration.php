<?php
include("../db_connection.php");  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form input
    $full_name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = strtolower(mysqli_real_escape_string($conn, trim($_POST['email'])));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm-password']));

    // Full Name Validation (at least 6 characters, only letters and spaces)
    if (!preg_match("/^[A-Za-z\s]{6,}$/", $full_name)) {
        echo "<script>
            alert('Full name must be at least 6 characters and only contain letters and spaces.');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Email Validation (must be valid Gmail)
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@gmail\.com$/', $email)) {
        echo "<script>
            alert('Please enter a valid Gmail address.');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Nepali phone number validation (starts with 96, 97, or 98 and 10 digits total)
    if (!preg_match("/^(9[6-8][0-9])\d{7}$/", $phone)) {
        echo "<script>
            alert('Please enter a valid 10-digit Nepali phone number.');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Password Strength Validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
        echo "<script>
            alert('Password must be at least 8 characters, including uppercase, lowercase, number, and special character.');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Confirm Password Match
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Passwords do not match.');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    // Check for existing email
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        echo "<script>
            alert('This email is already registered.');
            window.location.href = 'register.php';
        </script>";
        exit;
    }

    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $insert_query = "INSERT INTO users (full_name, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $full_name, $phone, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful! Please login to continue.');
            window.location.href = 'login.php';
        </script>";
    } else {
        echo "<script>
            alert('Something went wrong. Please try again.');
            window.location.href = 'register.php';
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>
