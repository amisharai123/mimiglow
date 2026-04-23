<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products - Mimi Glow</title>
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="Header/header.css">
    <link rel="stylesheet" href="Footer/footer.css">
</head>

<body class="body_class">

    <!-- Header -->
    <div id="header-placeholder"></div>
    <script>
        fetch("Header/header.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("header-placeholder").innerHTML = data;
            })
            .catch(error => console.error("Error loading header:", error));
    </script>

    <main>
        <?php
        require_once "db_connection.php";
        session_start();

        // Handle redirect category (from search.php)
        $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

        // Define category display names and section IDs
        $categories = [
            "Cleanser" => "cleansers",
            "Toner" => "toners",
            "Serum" => "serums",
            "Moisturizer" => "moisturizers",
            "Sunscreen" => "sunscreens"
        ];

        foreach ($categories as $categoryName => $sectionId) {
            $scrollTarget = ($selectedCategory === $categoryName) ? "data-scroll-target='true'" : "";
            echo "<section id='$sectionId' class='product-section' $scrollTarget>";
            echo "<h2 class='category-title'>" . htmlspecialchars($categoryName) . "s</h2>";
            echo "<div class='product-grid'>";

            $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-item">';
                    echo '<img src="Admin_Page/uploads/' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["product_name"]) . '">';
                    echo '<h3 class="product-name">' . htmlspecialchars($row["product_name"]) . '</h3>';
                    echo '<p class="product-price">Price: Rs ' . htmlspecialchars($row["product_price"]) . '</p>';

                    $description = $row["product_description"];
                    if (!empty($description) && $description !== "0") {
                        echo '<p class="product-description">' . htmlspecialchars($description) . '</p>';
                    }

                    echo '<button class="add-to-cart-btn" onclick="addToCart(\'' . $row["product_id"] . '\', \'' . $row["product_price"] . '\')">Add to Cart</button>';
                    echo '</div>';
                }
            } else {
                echo "<p>No products available in this category.</p>";
            }

            echo "</div></section>";
        }

        $conn->close();
        ?>
    </main>

    <!-- Footer -->
    <div id="footer-container"></div>
    <script>
        fetch("Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("footer-container").innerHTML = data;
            })
            .catch(error => console.error("Error loading footer:", error));
    </script>

    <script>
        function addToCart(productId, price) {
            function setCookie(name, value, days = 1) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = name + "=" + encodeURIComponent(value) + "; expires=" + date.toUTCString() + "; path=/; SameSite=Strict";
            }

            fetch("add_to_cart.php?action=add_to_cart&product_id=" + productId + "&quantity=1&price=" + price)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Item added to cart!");
                        window.location.href = "add_to_cart.php";
                    } else {
                        if (data.message === "Please login first") {
                            setCookie("pending_product_id", productId);
                            setCookie("pending_product_price", price);
                            alert("Please login first");
                            setTimeout(() => {
                                window.location.href = "Login/login.php";
                            }, 100);
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while adding the item to cart.");
                });
        }

        // Scroll to the matched category section if coming from search
        window.addEventListener("DOMContentLoaded", function () {
            const targetSection = document.querySelector("[data-scroll-target='true']");
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: "smooth" });
            }
        });
    </script>

</body>

</html>
