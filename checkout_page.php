<?php 
session_start();
require_once "db_connection.php";

// Redirect to login if not logged in
if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: Login/login.php");
    exit;
}

// Fetch active cart items
$stmt = mysqli_prepare($conn, "
    SELECT ci.*, p.product_name, p.image_url, p.product_price, o.order_id
    FROM cart_items ci
    JOIN orders o ON ci.order_id = o.order_id
    JOIN products p ON ci.product_id = p.product_id
    WHERE o.user_id = ? AND o.status = 'active'
");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['userID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cart_items = [];
$total = 0;
$cart_id = 0;
$productNames = [];

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
    $cart_id = $row['order_id'];
    $productNames[] = $row['product_name'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Mimi Glow</title>
    <style>
       body {
            font-family:"Times New Roman";
            background: #f5f7fa;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        .checkout-box {
            max-width: 850px;
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        }

        .checkout-box h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 25px;
            color: #2a2a2a;
            font-weight: 600;
        }

        .checkout-item {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            border-bottom: 1px solid #eee;
            padding-bottom: 12px;
        }

        .checkout-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .checkout-item-details {
            flex: 1;
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .checkout-item-details strong {
            display: block;
            font-size: 15px;
            color: #222;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #2a2a2a;
        }

        .form-group {
            margin-top: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            font-weight: 500;
            color: #444;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="tel"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
        }

        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="tel"]:focus {
            border-color: #4c5baf;
            box-shadow: 0 0 0 3px rgba(76,91,175,0.1);
        }

        .place-order-btn {
            display: block;
            width: 100%;
            margin-top: 25px;
            padding: 14px;
            background: #4c5baf;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .place-order-btn:hover {
            background: #3a4890;
        }

        #paymentStatus {
            margin-top: 20px;
            font-size: 14px;
            color: #2c662d;
        }
    </style>
</head>
<body>
    <div class="checkout-box">
        <h2>Review Your Order</h2>
        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty. <a href="products.php">Go back to shopping</a></p>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="checkout-item">
                    <img src="Admin_Page/uploads/<?php echo htmlspecialchars($item['image_url']); ?>">
                    <div class="checkout-item-details">
                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong><br>
                        Quantity: <?php echo $item['quantity']; ?><br>
                        Price: Rs.<?php echo number_format($item['price'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="total">
                Total: Rs.<?php echo number_format($total, 2); ?>
            </div>

            <!-- Customer Input Fields -->
            <div class="form-group">
                <label>Name:</label>
                <input type="text" id="customerName" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" id="customerEmail" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="tel" id="customerPhone" required>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" id="customerAddress" required>
            </div>

            <button class="place-order-btn" onclick="initiatePayment()">Proceed to Pay</button>

            <div id="paymentStatus"></div>
        <?php endif; ?>
    </div>

    <script>
        function initiatePayment() {
            const total = <?php echo $total; ?>;
            const productNames = "<?php echo implode(', ', $productNames); ?>";
            const customerName = document.getElementById('customerName').value;
            const customerEmail = document.getElementById('customerEmail').value;
            const customerPhone = document.getElementById('customerPhone').value;
            const customerAddress = document.getElementById('customerAddress').value;
            const userId = <?php echo $_SESSION['userID']; ?>;

            if (!customerName || !customerEmail || !customerPhone || !customerAddress) {
                alert("Please fill all customer details before proceeding.");
                return;
            }

            const paymentData = {
                amount: total,
                product_name: productNames,
                customer_name: customerName,
                customer_email: customerEmail,
                customer_phone: customerPhone,
                customer_address: customerAddress, // added address
                user_id: userId
            };

            document.getElementById('paymentStatus').innerHTML = '<p>Processing payment...</p>';

            fetch('http://localhost/Mimi_Glow/paymentapi/khalti_api.php?action=initiate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('paymentStatus').innerHTML = `
                        <p><strong>Payment initiated successfully!</strong></p>
                        <p>Order ID: ${data.order_id}</p>
                        <p>Payment ID: ${data.pidx}</p>
                        <p><a href="${data.payment_url}" target="_blank">Click here to pay with Khalti</a></p>
                        <p><em>You will be redirected to Khalti payment page</em></p>
                    `;
                    window.open(data.payment_url, '_blank');
                } else {
                    document.getElementById('paymentStatus').innerHTML = `
                        <p><strong>Error:</strong> ${data.error}</p>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('paymentStatus').innerHTML = `
                    <p><strong>Error:</strong> ${error.message}</p>
                `;
                console.error('Payment error:', error);
            });
        }
    </script>
</body>
</html>
