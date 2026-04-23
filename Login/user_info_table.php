<?php
include("../db_connection.php");  

// // Create the real `users` table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql_users)) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating 'users' table: " . mysqli_error($conn) . "<br>";
}

// Create payments table
$sql = "CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_order_id VARCHAR(100) UNIQUE NOT NULL,
    pidx VARCHAR(100),
    transaction_id VARCHAR(100),
    amount INT NOT NULL,
    status VARCHAR(50) DEFAULT 'initiated',
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),
    product_name VARCHAR(200),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'payments' created successfully! <br>";
} else {
    echo "Error creating 'payments': " . $conn->error . "<br>";
}

// FOR DELETING THE `users` TABLE //
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

// $sql_delete_users = "DROP TABLE IF EXISTS users";

// if (mysqli_query($conn, $sql_delete_users)) {
//     echo "Table 'users' deleted successfully.<br>";
// } else {
//     echo "Error deleting 'users' table: " . mysqli_error($conn) . "<br>";
// }

// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

// FOR DELETING THE `products` TABLE //
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

// $sql_delete_products = "DROP TABLE IF EXISTS products";

// if (mysqli_query($conn, $sql_delete_products)) {
//     echo "Table 'products' deleted successfully.<br>";
// } else {
//     echo "Error deleting 'products' table: " . mysqli_error($conn) . "<br>";
// }

//delete data form products table
// Disable foreign key checks
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// // Truncate the products table
// $sql_truncate = "TRUNCATE TABLE products";

// if (mysqli_query($conn, $sql_truncate)) {
//     echo "✅ Table 'products' emptied successfully. product_id reset to 1.";
// } else {
//     echo "❌ Error truncating 'products' table: " . mysqli_error($conn);
// }

// // Re-enable foreign key checks
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

//Real wala hai 


// Disable foreign key checks
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// // Delete all rows from products
// mysqli_query($conn, "DELETE FROM products");

// // Reset auto-increment to 1
// mysqli_query($conn, "ALTER TABLE products AUTO_INCREMENT = 1");

// // Re-enable foreign key checks
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

// echo "✅ All products deleted. product_id reset to 1.";





// // // Create the `products` table
$sql_products = "CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    product_price VARCHAR(50) NOT NULL,
    product_description TEXT,
    image_url VARCHAR(255),
    category VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql_products)) {
    echo "Table 'products' created successfully.<br>";
} else {
    echo "Error creating 'products' table: " . mysqli_error($conn) . "<br>";
}

// Create the `orders` real table
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    status ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";

if (mysqli_query($conn, $sql_orders)) { 
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating 'orders' table: " . mysqli_error($conn) . "<br>";
}

// Create the `orders` table
// $sql_orders = "CREATE TABLE IF NOT EXISTS orders (
//     order_id INT AUTO_INCREMENT PRIMARY KEY,
//     id INT NOT NULL,
//     status ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
// )";
// if (mysqli_query($conn, $sql_orders)) { 
//     echo "Table 'orders' created successfully.<br>";
// } else {
//     echo "Error creating 'orders' table: " . mysqli_error($conn) . "<br>";
// }


// Disable foreign key checks to avoid errors while dropping table
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

// // Drop the 'orders' table if it exists
// $sql_delete_orders = "DROP TABLE IF EXISTS orders";

// if (mysqli_query($conn, $sql_delete_orders)) {
//     echo "Table 'orders' deleted successfully.<br>";
// } else {
//     echo "Error deleting 'orders' table: " . mysqli_error($conn) . "<br>";
// }

// // Re-enable foreign key checks after dropping table
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");


// Create the `cart_items` table
$sql_cart_items = "CREATE TABLE IF NOT EXISTS cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql_cart_items)) {
    echo "Table 'cart_items' created successfully.<br>";
} else {
    echo "Error creating 'cart_items' table: " . mysqli_error($conn) . "<br>";
}

// FOR DELETING THE cart_items TABLE //
// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

// $sql_delete_cart_items = "DROP TABLE IF EXISTS cart_items";

// if (mysqli_query($conn, $sql_delete_cart_items)) {
//     echo "Table 'cart_items' deleted successfully.<br>";
// } else {
//     echo "Error deleting 'cart_items' table: " . mysqli_error($conn) . "<br>";
// }

// mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");




// Create the `order_items` table
$sql_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql_order_items)) {
    echo "Table 'order_items' created successfully.<br>";
} else {
    echo "Error creating 'order_items' table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>
