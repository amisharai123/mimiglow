<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.php");
    exit();
}

include('../db_connection.php');

// Product Count
$product_count_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = mysqli_query($conn, $product_count_query);
$product_count = ($product_result && mysqli_num_rows($product_result) > 0) ? mysqli_fetch_assoc($product_result)['total_products'] : 0;

// User Count
$user_count_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = mysqli_query($conn, $user_count_query);
$user_count = ($user_result && mysqli_num_rows($user_result) > 0) ? mysqli_fetch_assoc($user_result)['total_users'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Mimi Glow</title>
  <link rel="stylesheet" href="admin_dash.css?v=<?php echo time(); ?>">
</head>
<body>

<div id="header-placeholder"></div>
<script>
  fetch('admin_header.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('header-placeholder').innerHTML = data;
    })
    .catch(error => console.error('Error loading header:', error));
</script>

<section class="admin-dashboard">
  <h1>Welcome, Admin!</h1>
  <div class="stats">
    <div class="stat-item">
      <h3>Total Products</h3>
      <p><?php echo $product_count; ?></p>
    </div>
    <div class="stat-item">
      <h3>Total Users</h3>
      <p><?php echo $user_count; ?></p>
    </div>
  </div>
</section>

</body>
</html>
