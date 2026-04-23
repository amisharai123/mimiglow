<?php 
session_start(); 
include("../db_connection.php");  

// Admin Credentials
$adminEmail = "masteradmin985@gmail.com"; 
$adminPassword = "MasterAdmin10$";  

// Get and sanitize user input
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : ''; 
$password = isset($_POST['password']) ? trim($_POST['password']) : '';  

// Validate Email
if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9.%+-]*@gmail\.com$/', $email)) {
    echo "<script>
        alert('Invalid email format. Please enter a valid Gmail address.');
        window.location.href = '../Login/login.php';
    </script>";
    exit();
}

// Validate Password
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password)) {
    echo "<script>
        alert('Password must be at least 8 characters, include uppercase, lowercase, number, and special character.');
        window.location.href = '../Login/login.php';
    </script>";
    exit();
}

// Admin Login Check
if ($email === $adminEmail && $password === $adminPassword) {
    $_SESSION['userEmail'] = $adminEmail;
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['userRole'] = 'Admin';
    header("Location: ../Admin_Page/admin_dash.php");
    exit();
}

// User Login Validation
$stmt = $conn->prepare("SELECT id, full_name, password, phone FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['userID'] = $row['id'];
        $_SESSION['username'] = $row['full_name'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['userEmail'] = $email;
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userRole'] = 'User';

        // Redirect based on cart state
        if (isset($_SESSION['pending_product_id'])) {
            header("Location: ../Header/add_to_cart.php?action=add_to_cart&product_id=" . 
                   $_SESSION['pending_product_id'] . 
                   "&quantity=1&price=" . 
                   $_SESSION['pending_product_price']);
            unset($_SESSION['pending_product_id']);
            unset($_SESSION['pending_product_price']);
            exit();
        } else {
            echo "<script>
                alert('Welcome to Mimi Glow!');
                window.location.href = '../products.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Incorrect password. Please try again.');
            window.location.href = '../Login/login.php';
        </script>";
        exit();
    }
} else {
    echo "<script>
        alert('No account found with that email. Please register.');
        window.location.href = '../Login/register.php';
    </script>";
    exit();
}
// If "Remember Me" is checked
// if (isset($_POST['remember_me'])) {
//     setcookie('remember_email', $email, time() + (86400 * 30), "/"); // 30 days
// } else {
//     setcookie('remember_email', '', time() - 3600, "/"); // Delete cookie
// }

$stmt->close();
$conn->close();
?>
