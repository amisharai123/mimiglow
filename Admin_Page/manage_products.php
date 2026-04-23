<?php
session_start();
include('../db_connection.php');

// Delete Product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM products WHERE product_id = $delete_id";

    if (mysqli_query($conn, $delete_query)) {
        echo "<p style='color:green; text-align:center;'>Product deleted successfully!</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Error deleting product: " . mysqli_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Mimi Glow</title>
    <style>
        body {
            font-family:'Times New Roman', Times, serif;
            background-color: #F8F4E3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #c2185b;;
        }

        .add-product-link {
            display: block;
            width: fit-content;
            margin: 1rem auto;
            padding: 10px 20px;
            background-color: #ff69b4;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .add-product-link:hover {
            background-color: #ffb6c1;
        }

        .product {
            background-color: #F8F4E3;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .product p {
            margin: 0.3rem 0;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            color: white;
            transition: background-color 0.3s ease;
        }

        .actions .update {
            background-color: #007bff;
        }

        .actions .update:hover {
            background-color: #0056b3;
        }

        .actions .delete {
            background-color: #dc3545;
        }

        .actions .delete:hover {
            background-color: #C5B358;
        }
    </style>
</head>
<body>

<!-- Header -->
<div id="header-placeholder"></div>
<script>
    fetch('../Admin_Page/admin_header.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        })
        .catch(error => console.error('Error loading header:', error));
</script>

<section class="container">
    <h1>Manage Products</h1>
    <a href="add_product.php" class="add-product-link">➕ Add New Product</a>

    <?php
    // Fetch all products
    $sql = "SELECT * FROM products ORDER BY product_id DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<p><strong>Product Name:</strong> " . htmlspecialchars($row['product_name']) . "</p>";
            echo "<p><strong>Price:</strong> Rs." . htmlspecialchars($row['product_price']) . "</p>";
            echo "<p><strong>Description:</strong> " . htmlspecialchars($row['product_description']) . "</p>";

            echo "<div class='actions'>";
            echo "<a href='update_products.php?update_id=" . $row['product_id'] . "' class='update'>✏️ Update</a>";
            echo "<a href='?delete_id=" . $row['product_id'] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this product?\");'>🗑️ Delete</a>";
            echo "</div>";

            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No products found.</p>";
    }
    ?>
</section>
</body>
</html>
