<!DOCTYPE html>
<html>
<head>
    <title>Simple Khalti Payment Test</title>
</head>
<body>
    <h1>Simple E-commerce Test Page</h1>
    
    <!-- Product List -->
    <h2>Available Products</h2>
    <table border="1">
        <tr>
            <th>Product</th>
            <th>Price (NPR)</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>T-Shirt</td>
            <td>500</td>
            <td><button onclick="addToCart('T-Shirt', 500)">Add to Cart</button></td>
        </tr>
        <tr>
            <td>Jeans</td>
            <td>1200</td>
            <td><button onclick="addToCart('Jeans', 1200)">Add to Cart</button></td>
        </tr>
        <tr>
            <td>Sneakers</td>
            <td>2500</td>
            <td><button onclick="addToCart('Sneakers', 2500)">Add to Cart</button></td>
        </tr>
        <tr>
            <td>Book</td>
            <td>300</td>
            <td><button onclick="addToCart('Book', 300)">Add to Cart</button></td>
        </tr>
    </table>

    <hr>

    <!-- Cart Section -->
    <h2>Shopping Cart</h2>
    <div id="cart">
        <p>Your cart is empty</p>
    </div>
    <p><strong>Total: NPR <span id="total">0</span></strong></p>
    <button onclick="clearCart()">Clear Cart</button>

    <hr>

    <!-- Customer Information Form -->
    <h2>Customer Information & Checkout</h2>
    <form id="checkoutForm">
        <table>
            <tr>
                <td>Customer Name:</td>
                <td><input type="text" id="customer_name" required placeholder="Enter your full name"></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" id="customer_email" required placeholder="your@email.com"></td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td><input type="text" id="customer_phone" required placeholder="98xxxxxxxx"></td>
            </tr>
        </table>
        <br>
        <button type="submit">Pay with Khalti</button>
    </form>

    <hr>

    <!-- Payment Status -->
    <h2>Payment Status</h2>
    <div id="paymentStatus"></div>

    <script>
        let cart = [];
        let total = 0;

        function addToCart(productName, price) {
            // Check if product already exists in cart
            const existingItem = cart.find(item => item.name === productName);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    name: productName,
                    price: price,
                    quantity: 1
                });
            }
            
            updateCartDisplay();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }

        function updateQuantity(index, newQuantity) {
            if (newQuantity <= 0) {
                removeFromCart(index);
            } else {
                cart[index].quantity = parseInt(newQuantity);
                updateCartDisplay();
            }
        }

        function updateCartDisplay() {
            const cartDiv = document.getElementById('cart');
            const totalSpan = document.getElementById('total');
            
            if (cart.length === 0) {
                cartDiv.innerHTML = '<p>Your cart is empty</p>';
                total = 0;
            } else {
                let cartHtml = '<table border="1"><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>';
                total = 0;
                
                cart.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    
                    cartHtml += `
                        <tr>
                            <td>${item.name}</td>
                            <td>NPR ${item.price}</td>
                            <td>
                                <input type="number" value="${item.quantity}" min="1" 
                                       onchange="updateQuantity(${index}, this.value)" style="width: 50px;">
                            </td>
                            <td>NPR ${subtotal}</td>
                            <td><button onclick="removeFromCart(${index})">Remove</button></td>
                        </tr>
                    `;
                });
                
                cartHtml += '</table>';
                cartDiv.innerHTML = cartHtml;
            }
            
            totalSpan.textContent = total;
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
        }

        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (cart.length === 0) {
                alert('Please add items to cart first!');
                return;
            }
            
            const customerName = document.getElementById('customer_name').value;
            const customerEmail = document.getElementById('customer_email').value;
            const customerPhone = document.getElementById('customer_phone').value;
            
            // Create product name from cart items
            const productNames = cart.map(item => ${item.name} (${item.quantity}x)).join(', ');
            
            // Prepare payment data
            const paymentData = {
                amount: total, // Amount in NPR
                product_name: productNames,
                customer_name: customerName,
                customer_email: customerEmail,
                customer_phone: customerPhone
            };
            
            // Show loading status
            document.getElementById('paymentStatus').innerHTML = '<p>Processing payment...</p>';
            
            // Make payment request
            fetch('khalti_api.php?action=initiate', {
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
                    
                    // Automatically redirect to payment page
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
        });

        // Check payment status function
        function checkPaymentStatus(orderId) {
            fetch(khalti_api.php?action=status&order_id=${orderId})
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusDiv = document.getElementById('paymentStatus');
                    statusDiv.innerHTML += `
                        <h3>Payment Status Check:</h3>
                        <p>Status: <strong>${data.status}</strong></p>
                        <p>Transaction ID: ${data.transaction_id || 'N/A'}</p>
                        <p>Amount: NPR ${data.amount / 100}</p>
                    `;
                }
            });
        }

        // Sample data button for testing
        function fillSampleData() {
            document.getElementById('customer_name').value = 'Test Bahadur';
            document.getElementById('customer_email').value = 'test@example.com';
            document.getElementById('customer_phone').value = '9800000001';
            
            // Add sample items to cart
            addToCart('T-Shirt', 500);
            addToCart('Book', 300);
        }
    </script>

    <hr>
    
    <!-- Testing Helpers -->
    <h2>Testing Helpers</h2>
    <button onclick="fillSampleData()">Fill Sample Data</button>
    <br><br>
    
    <h3>Test Khalti Credentials:</h3>
    <ul>
        <li><strong>Khalti ID:</strong> 9800000000, 9800000001, 9800000002, 9800000003, 9800000004, 9800000005</li>
        <li><strong>MPIN:</strong> 1111</li>
        <li><strong>OTP:</strong> 987654</li>
    </ul>
    
    <h3>Manual Payment Status Check:</h3>
    <input type="text" id="orderIdCheck" placeholder="Enter Order ID">
    <button onclick="checkPaymentStatus(document.getElementById('orderIdCheck').value)">Check Status</button>

</body>
</html>
