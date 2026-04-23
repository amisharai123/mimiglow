<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.php");
    exit();
}
include('../db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $product_price = trim($_POST['product_price']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (!is_writable('uploads')) {
        die("Uploads directory is not writable.");
    }

    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, product_price, product_description, image_url, category) 
        VALUES (?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssdss", $product_name, $product_price, $description, $image, $category);
    
    if ($stmt->execute()) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Mimi Glow</title>
    <!-- <link rel="stylesheet" href="../Admin_Page/admin_dash.css"> -->

    <style>
        body {
            font-family:'Times New Roman', Times, serif;
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
            font-family:'Times New Roman', Times, serif;
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
    <h2>Add New Skin Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required>

        <label for="product_price">Price (Rs):</label>
        <input type="number" name="product_price" id="product_price" step="0.01" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
