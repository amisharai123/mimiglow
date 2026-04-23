<!-- admin_header.php -->
<?php
session_start();
// if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'Admin') {
//     header("Location: ../Login/login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mimi Glow Admin Panel</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      background-color: #fff5f8;
    }

    header {
      background-color: #ffb6c1;
      color: white;
      padding: 15px 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar {
      display: flex;
      align-items: center;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .navbar .logo {
      display: flex;
      align-items: center;
      margin-right: 40px;
    }

    .navbar .logo img {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
      box-shadow: 0 0 6px rgba(255, 255, 255, 0.7);
    }

    .navbar .logo span {
      font-size: 20px;
      font-weight: bold;
      color: #333;
    }

    nav ul {
      list-style-type: none;
      display: flex;
      align-items: center;
      gap: 45px;
      flex-wrap: nowrap;
    }

    nav ul li a {
      color: #333;
      text-decoration: none;
      font-size: 1em;
      padding: 6px 10px;
      border-radius: 5px;
      font-weight:bolder;
      transition: background-color 0.3s ease, color 0.3s ease;
      margin-right:4px;
       white-space: nowrap; /* prevent text from breaking */
    }

    nav ul li a:hover {
      background-color: #ff69b4;
      color: white;
    }

    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }

      nav ul {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="navbar">
    <!-- Logo -->
    <div class="logo">
      <img src="../web_image/logo.jpg" alt="Mimi Glow Logo">
      <span>Mimi Glow</span>
    </div>

    <!-- Navigation -->
    <nav>
      <ul>
        <li><a href="admin_dash.php">Dashboard</a></li>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="add_product.php">Add Products</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="admin_orders.php">Orders</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

</body>
</html>
