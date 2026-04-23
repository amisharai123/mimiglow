
<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mimi Glow - Home</title>
    <link rel="stylesheet" href="home.css"> 
    <link rel="stylesheet" href="Header/header.css">
    <link rel="stylesheet" href="Footer/footer.css">
</head>
<body>


 <!-- JS for the Header -->
 <div id="header-placeholder"></div>
    <script>
        fetch('Header/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

<?php
if (isset($_SESSION['username'])) {
    echo "<div class='welcome-banner'>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</div>";
}

?>


<!-- Hero Section -->
<div class="hero">
    <h1>Glow Naturally, Shine Confidently</h1>
    <p>Discover the skin care products that love your skin just as much as you do.</p>
    <a href="products.php" class="shop-now-btn">Shop Now</a>
</div>

<!-- Features Section -->
<section class="front-images">
    <div class="image">
        <img src="web_image/front_image02.jpg" alt="image 2">
        <h3>All Natural Ingredients</h3>
        <p>Our products are made with the finest, all-natural ingredients for your skin.</p>
    </div>
    <div class="image">
        <img src="web_image/front_image01.jpg" alt="image 1">
        <h3>Perfect for All Skin Types</h3>
        <p>No matter your skin type, we have a product for you.</p>
    </div>
    <div class="image">
        <img src="web_image/front_image03.jpg" alt="image 3">
        <h3>Eco-Friendly Packaging</h3>
        <p>We care about the planet, and our packaging reflects that.</p>
    </div>
</section>

  <!-- JS for footer -->
  <div id="footer-container"></div>
    <script>
        fetch("Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>


</body>
</html>
