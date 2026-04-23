<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mimi Glow - Home</title>
    <link rel="stylesheet" href="header.css"> 
</head>
<body>

<!-- Header and Navigation -->
<header>
    <nav class="navbar">
        <div class="logo">
            <a href="home_page.php">
                <img src="/mimi_glow/Web_image/logo.jpg" alt="Mimi Glow Logo">
            </a>
            <span id="title">Mimi Glow</span>
        </div>

      <!-- Search Bar -->
      <form class="search-form" action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search products..." required>
            <button type="submit">Search</button>
        </form>


        <ul class="nav-links">
            <li><a id="home" href="home_page.php">Home</a></li>
            <li><a id="products" href="products.php">Products</a></li>
            <li><a id="cart" href="add_to_cart.php">Cart</a></li>
            <li><a id="about_us" href="About_us.php">About Us</a></li>
            <?php
                if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
                    echo '<li><a id="log_out" href="/mimi_glow/Admin_Page/logout.php">Log Out</a></li>
';
                } else {
                    echo '<li><a id="login" href="/mimi_glow/Login/login.php">Login</a></li>
';
                }
            ?>
        </ul>
    </nav>
</header>
</body>
</html>
