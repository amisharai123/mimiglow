<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mimi Glow | Skin Care Products</title>
  <link rel="stylesheet" href="products.css">
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

  <!-- Main Product Section -->
  <section class="products-section">
    <div class="product-container">
      <div class="section-title">
        <h2>Our Best COSRX Skin Care Products</h2>
        <p>Discover the perfect skincare solutions for your skin type.</p>
      </div>

      <!--For Cleanser Section-->
      <div class="product-section">
        <h3>Cleanser</h3>
        <div class="product-items">
          <div class="product-item">
            <img src="web_image/cleanser01.jpg" alt="COSRX Low pH Good Morning Gel Cleanser">
            <h4>COSRX Low pH Good Morning Gel Cleanser</h4>
            <p class="product-price">Rs.1500</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/cleanser02.jpg" alt="COSRX Salicylic Acid Daily Cleanser">
            <h4>COSRX Salicylic Acid Daily Cleanser</h4>
            <p class="product-price">Rs.2100</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>

          <div class="product-item">
            <img src="web_image/cleanser03.jpg" alt="COSRX Low pH First Cleansing Milk Gel">
            <h4>COSRX Low pH First Cleansing Milk Gel</h4>
            <p class="product-price">Rs.2500</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>

          <div class="product-item">
            <img src="web_image/cleanser05.jpg" alt="COSRX Pure Fit Cica Cleanser">
            <h4>COSRX Pure Fit Cica Cleanser</h4>
            <p class="product-price">Rs.1800</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
        </div>
      </div>
     <!-- For Toner Section -->
      <div class="product-section">
        <div id="toner-section"></div>
        <h3>Toner</h3>
        <div class="product-items">
          <div class="product-item">
            <img src="web_image/toner01.jpg" alt="COSRX AHA/BHA Clarifying Treatment Toner">
            <h4>COSRX AHA/BHA Clarifying Treatment Toner</h4>
            <p class="product-price">Rs.1600</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/toner02.jpg" alt="COSRX Full Fit Propolis Synergy Toner">
            <h4>COSRX Full Fit Propolis Synergy Toner</h4>
            <p class="product-price">Rs.2200</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>

          <div class="product-item">
            <img src="web_image/toner03.jpg" alt="COSRX Hydrium Watery Toner">
            <h4>COSRX Hydrium Watery Toner</h4>
            <p class="product-price">Rs.2000</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/toner04.jpg" alt="COSRX Centella Water Alcohol-Free Toner">
            <h4>COSRX Centella Water Alcohol-Free Toner</h4>
            <p class="product-price">Rs.2100</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
        </div>
      </div>

     <!-- For Serum Section -->
      <div class="product-section">
        <h3>Serum</h3>
        <div class="product-items">
          <div class="product-item">
            <img src="web_image/serum01.jpg" alt="COSRX Snail Mucin 96% Power Essence">
            <h4>COSRX Snail Mucin 96% Power Essence</h4>
            <p class="product-price">Rs.2600</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/serum02.jpg" alt="COSRX The Niacinamide 15 Serum">
            <h4>COSRX The Niacinamide 15 Serum</h4>
            <p class="product-price">Rs.3000</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/serum03.jpg" alt="COSRX The Vitamin C 23 Serum">
            <h4>COSRX The Vitamin C 23 Serum</h4>
            <p class="product-price">Rs.2900</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/serum04.jpg" alt="COSRX 6 Peptide Skin Booster Serum">
            <h4>COSRX 6 Peptide Skin Booster Serum</h4>
            <p class="product-price">Rs.2200</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          
        </div>
      </div>
     <!-- For Moisturizer Section -->
      <div class="product-section">
        <h3>Moisturizer Cream</h3>
        <div class="product-items">
          <div class="product-item">
            <img src="web_image/moisturizer01.jpg" alt="COSRX Advanced Snail 92 All In One Cream">
            <h4>COSRX Advanced Snail 92 All In One Cream</h4>
            <p class="product-price">Rs.2700</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/moisturizer02.jpg" alt="COSRX Hyaluronic Acid Intensive Cream">
            <h4>COSRX Hyaluronic Acid Intensive Cream</h4>
            <p class="product-price">Rs.2400</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/moisturizer03.jpg" alt="COSRX Balancium Comfort Ceramide Cream">
            <h4>COSRX Balancium Comfort Ceramide Cream</h4>
            <p class="product-price">Rs.2600</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/moisturizer04.jpg" alt="COSRX Oil-Free Ultra-Moisturizing Lotion">
            <h4>COSRX Oil-Free Ultra-Moisturizing Lotion</h4>
            <p class="product-price">Rs.2200</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          
        </div>
      </div>
     
      <!-- For Sunscreen Section -->
      <div class="product-section">
        <h3>Sunscreen Cream</h3>
        <div class="product-items">
          <div class="product-item">
            <img src="web_image/suncream01.jpg" alt="COSRX Aloe Soothing Sun Cream SPF50+ PA+++">
            <h4>COSRX Aloe Soothing Sun Cream SPF50+ PA+++</h4>
            <p class="product-price">Rs.2300</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/suncream02.jpg" alt="COSRX Ultra-Light Invisible Sunscreen SPF50+">
            <h4>COSRX Ultra-Light Invisible Sunscreen SPF50+</h4>
            <p class="product-price">Rs.2500</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/suncream03.jpg" alt="COSRX Vitamin E Vitalizing Sunscreen SPF50+">
            <h4>COSRX Vitamin E Vitalizing Sunscreen SPF50+</h4>
            <p class="product-price">Rs.2600</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          <div class="product-item">
            <img src="web_image/suncream04.jpg" alt="COSRX Shield Fit Snail Essence Sun SPF50+ PA+++">
            <h4>COSRX Shield Fit Snail Essence Sun SPF50+ PA+++</h4>
            <p class="product-price">Rs.2100</p>
            <a href="#" class="btn">Add to Cart</a>
          </div>
          
        </div>
      </div>


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
<style>
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cart-summary {
            margin-top: 20px;
            text-align: right;
        }
        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .alert-success, .alert-pending-product {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>