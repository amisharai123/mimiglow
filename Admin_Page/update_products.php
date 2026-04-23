<?php
session_start();
include('../db_connection.php');

// Fetch product details
$product = null;
if (isset($_GET['update_id'])) {
    $id = intval($_GET['update_id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Product not found.");
    }
}

// Update product on POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (!$image) {
        $image = $product['image_url'];
    } else {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_price=?, product_description=?, image_url=?, category=? WHERE product_id=?");
    $stmt->bind_param("sdsissi", $name, $price, $description, $image, $category, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated!";
        header("Location: manage_products.php");
        exit;
    } else {
        die("Error: " . $stmt->error);
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product | Mimi Glow</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #FBEFEF;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 550px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #c2185b;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: bold;
            color: #444;
        }

        input, textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        img {
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 0.9rem;
            background-color: #ff69b4;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Times New Roman', Times, serif;
        }

        button:hover {
            background-color: #ffb6c1;
        }
    </style>
</head>
<body>

<div id="header-placeholder"></div>
<script>
    fetch('admin_header.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header-placeholder').innerHTML = data;
        })
        .catch(error => console.error('Header load error:', error));
</script>

<div class="form-container">
    <h2>Update Skin Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">

        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

        <label for="price">Price (Rs):</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['product_price']) ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($product['product_description']) ?></textarea>

        <!-- <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="= htmlspecialchars($product['stock']) ?>" required> -->

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?= htmlspecialchars($product['category']) ?>" required>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image">
        <p>Current Image:</p>
        <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" width="100" alt="Current Product Image">

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>
