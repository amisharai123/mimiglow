<?php
// Database configuration
$db_host = 'localhost';
$db_name = 'mimi_glow';
$db_user = 'root';
$db_pass = '';

// Khalti API configuration
$khalti_secret_key = 'YOUR_KHALTI_SECRET_KEY'; // Replace with your actual key
$khalti_api_url = 'https://dev.khalti.com/api/v2/'; // Use https://khalti.com/api/v2/ for production
$website_url = 'http://localhost/Mimi_Glow/'; // Update with your domain
$return_url = 'http://localhost/Mimi_Glow/paymentapi/khalti_api.php?action=callback'; // Update with your domain

// Database connection
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

// Helper functions
function generateOrderId() {
    return 'ORDER_' . time() . '_' . rand(1000, 9999);
}
function khaltiApiRequest($url, $data, $secret_key) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Authorization: key ' . $secret_key,
            'Content-Type: application/json',
        ),
    ));
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);
    
    if ($error) {
        throw new Exception("CURL Error: " . $error);
    }
    $decodedResponse = json_decode($response, true);
    return $decodedResponse;
    if ($httpCode !== 200) {
        throw new Exception("API Error: " . ($decodedResponse['detail'] ?? 'Unknown error'));
    }
    
    return $decodedResponse;
}

function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function renderPaymentResult($success, $title, $message, $details = []) {
    $statusColor = $success ? '#10B981' : '#EF4444';
    $bgGradient = $success ? 
    
        'linear-gradient(135deg, #10B981 0%, #059669 100%)' : 
        'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)';
    
  $iconSvg = $success ? 
        '<div class="success-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" fill="#10B981"/>
                <path d="m9 12 2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>' :
        '<div class="error-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" fill="#EF4444"/>
                <path d="M15 9l-6 6m0-6l6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>';

         // After $iconSvg
    $className = $success ? 'container success' : 'container failed';
       

           echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Payment Result - Khalti</title>
    <title>Payment Result - Khalti</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #141E30, #243B55);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        color: #fff;
    }
         .container {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(15px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        padding: 40px;
        max-width: 520px;
        width: 100%;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .status-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }
    .status-success { color: #10B981; }
    .status-failed { color: #EF4444; }
    .status-initiated { color: #FBBF24; }
    h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }
          .message {
        font-size: 16px;
        color: #E5E7EB;
        margin-bottom: 24px;
    }
    .details {
        text-align: left;
        background: rgba(255, 255, 255, 0.06);
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .detail-row:last-child {
        border-bottom: none;
    }
         .detail-label {
        font-weight: 500;
        color: #cbd5e1;
    }
    .detail-value {
        font-weight: 600;
        color: #f1f5f9;
    }
    .btn-container {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s ease;
        border: none;
        cursor: pointer;
    }
         .btn-primary {
        background: #6366F1;
        color: #fff;
    }
    .btn-primary:hover {
        background: #4F46E5;
        transform: translateY(-1px);
    }
    .btn-secondary {
        background: #E5E7EB;
        color: #111827;
    }
    .btn-secondary:hover {
        background: #D1D5DB;
    }
    .khalti-logo {
        width: 80px;
        margin-bottom: 20px;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
    }
           @media (max-width: 600px) {
        .container {
            padding: 30px 20px;
        }
        .btn-container {
            flex-direction: column;
        }
        .btn {
            width: 100%;
        }
    }
        
      .container.success {
    border: 2px solid #22c55e;           /* Vibrant green border */
    background-color: rgba(34, 197, 94, 0.15);  /* Soft green translucent */
    color: #166534;                      /* Dark green text */
    animation: slideIn 0.6s ease;
}

.container.failed {
    border: 2px solid #ef4444;           /* Bright red border */
    background-color: rgba(239, 68, 68, 0.15);  /* Soft red translucent */
    color: #7f1d1d;                      /* Dark red text */
    animation: slideIn 0.6s ease;
}

.details {
    text-align: left;
    background: rgba(0, 0, 0, 0.25);    /* Darker translucent background for contrast */
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 30px;
    color: #e0e7ff;                     /* Soft off-white text */
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3); /* subtle shadow to lift details box */
}

.detail-label {
    color: #a5b4fc;                    /* Soft blue label text */
}

.detail-value {
    color: #f0f9ff;                    /* Bright, crisp value text */
    font-weight: 700;
}

/* Icons */
.success-icon svg {
    fill: #22c55e;
    stroke: white;
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
    animation: popIn 0.4s ease;
}

.error-icon svg {
    fill: #ef4444;
    stroke: white;
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
    animation: popIn 0.4s ease;
}
/* Animation for container sliding in */
@keyframes slideIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Animation for icon popping in */
@keyframes popIn {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Apply animation to container */
.container.success,
.container.failed {
    animation: slideIn 0.6s ease;
}

/* Apply animation to icons */
.success-icon svg,
.error-icon svg {
    animation: popIn 0.4s ease;
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
}

</style>
</head>
<body>
    <div class='$className'>
        <div class='pulse'>
            <img src='/Mimi_Glow/Web_image/khalti.png' alt='Khalti' class='khalti-logo'>
        </div>
        
        $iconSvg
        
        <h1>$title</h1>
        <p class='message'>$message</p>";
        
    if (!empty($details)) {
        echo "<div class='details'>";
        foreach ($details as $label => $value) {
            echo "<div class='detail-row'>
                    <span class='detail-label'>$label</span>
                    <span class='detail-value'>$value</span>
                  </div>";
        }
        echo "</div>";
    }
     echo "<div class='btn-container'>
            <a href='$GLOBALS[website_url]/products.php' class='btn btn-primary'>Continue Shopping</a>
            <button onclick='window.print()' class='btn btn-secondary'>Print Receipt</button>
        </div>
    </div>
    
    <script>
        // Auto-close after 30 seconds if opened in popup
        if (window.opener) {
            setTimeout(() => {
                if (confirm('Close this window?')) {
                    window.close();
                }
            }, 30000);
        }
    </script>
</body>
</html>";
    exit;
}
// Handle different actions
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'initiate':
        // POST /khalti-payment.php?action=initiate
        // Required: amount, product_name, customer_name, customer_email, customer_phone
        
        try {
            // Validate required fields
            $required_fields = ['amount', 'product_name', 'customer_name', 'customer_email', 'customer_phone'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Field '$field' is required");
                }
            }
             $purchase_order_id = generateOrderId();
            $amount = (int)($_POST['amount']); // Convert to paisa
            $product_name = $_POST['product_name'];
            $customer_name = $_POST['customer_name'];
            $customer_email = $_POST['customer_email'];
            $customer_phone = $_POST['customer_phone'];
            $userId = $_POST['user_id'];
          
            // Save payment record
            $stmt = $pdo->prepare("INSERT INTO payments (purchase_order_id, amount, customer_name, customer_email, customer_phone, product_name, status, user_id) VALUES (?, ?, ?, ?, ?, ?, 'initiated', ?)");
            $stmt->execute([$purchase_order_id, $amount, $customer_name, $customer_email, $customer_phone, $product_name, $userId]);
             // Prepare payment data
            $payment_data = [
                'return_url' => $return_url,
                'website_url' => $website_url,
                'amount' => $amount*100,
                'purchase_order_id' => $purchase_order_id,
                'purchase_order_name' => $product_name,
                'customer_info' => [
                    'name' => $customer_name,
                    'email' => $customer_email,
                    'phone' => $customer_phone
                ]
            ];
            
            // Make API request to Khalti
            $response = khaltiApiRequest($khalti_api_url . 'epayment/initiate/', $payment_data, $khalti_secret_key);
            // Update payment with pidx
            $stmt = $pdo->prepare("UPDATE payments SET pidx = ? WHERE purchase_order_id = ?");
            $stmt->execute([$response['pidx'], $purchase_order_id]);
            
            sendJsonResponse([
                'success' => true,
                'payment_url' => $response['payment_url'],
                'pidx' => $response['pidx'],
                'order_id' => $purchase_order_id,
                'message' => 'Payment initiated successfully'
            ]);
            
        } catch (Exception $e) {
            sendJsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
        break;

         case 'callback':
        // GET /khalti-payment.php?action=callback
        // This handles the redirect from Khalti after payment - NOW WITH UI
        
        $pidx = $_GET['pidx'] ?? '';
        $status = $_GET['status'] ?? '';
        $transaction_id = $_GET['transaction_id'] ?? '';
        $amount = $_GET['amount'] ?? '';
        $purchase_order_id = $_GET['purchase_order_id'] ?? '';
        
        try {
            if ($status === 'Completed') {
                // Verify payment with lookup API
                $lookup_data = ['pidx' => $pidx];
                $lookup_response = khaltiApiRequest($khalti_api_url . 'epayment/lookup/', $lookup_data, $khalti_secret_key);
                 if ($lookup_response['status'] === 'Completed') {
                    // Update payment status
                    $stmt = $pdo->prepare("UPDATE payments SET status = 'completed', transaction_id = ?, updated_at = NOW() WHERE pidx = ?");
                    $stmt->execute([$lookup_response['transaction_id'], $pidx]);

                    // Clear the cart - get user_id from payment record and clear their active cart
                    $stmt = $pdo->prepare("SELECT user_id FROM payments WHERE pidx = ?");
                    $stmt->execute([$pidx]);
                    $payment_record = $stmt->fetch(PDO::FETCH_ASSOC);

                   if ($payment_record && $payment_record['user_id']) {
                        // Get active order for this user
                        $stmt = $pdo->prepare("SELECT order_id FROM orders WHERE user_id = ? AND status = 'active'");
                        $stmt->execute([$payment_record['user_id']]);
                        $active_order = $stmt->fetch(PDO::FETCH_ASSOC);
    
                       if ($active_order) {
                            // Update order status to completed and clear cart items
                            $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE order_id = ?");
                             $stmt->execute([$active_order['order_id']]);
        
                            // Optionally delete cart items (or keep them for order history)
                            // $stmt = $pdo->prepare("DELETE FROM cart_items WHERE order_id = ?");
                            // $stmt->execute([$active_order['order_id']]);
                        }
                    }
                    
                    // Get payment details for display
                    $stmt = $pdo->prepare("SELECT * FROM payments WHERE pidx = ?");
                    $stmt->execute([$pidx]);
                    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Render success UI
                    renderPaymentResult(
                        true,
                        'Payment Successful!',
                        'Your payment has been processed successfully. Thank you for your purchase.',
                         [
                            'Order ID' => $purchase_order_id,
                            'Transaction ID' => $lookup_response['transaction_id'],
                            'Amount' => 'NPR ' . number_format($lookup_response['total_amount'] / 100, 2),
                            'Product' => $payment['product_name'] ?? 'N/A',
                            'Customer' => $payment['customer_name'] ?? 'N/A',
                            'Payment Method' => 'Khalti Digital Wallet',
                            'Date' => date('M d, Y h:i A')
                        ]
                    );
                } else {
                    throw new Exception("Payment verification failed. Status: " . $lookup_response['status']);
                }
            } else {
                  // Handle failed/canceled payments
                $stmt = $pdo->prepare("UPDATE payments SET status = ?, updated_at = NOW() WHERE pidx = ?");
                $stmt->execute([strtolower(str_replace(' ', '_', $status)), $pidx]);
                
                // Get payment details for display
                $stmt = $pdo->prepare("SELECT * FROM payments WHERE pidx = ?");
                $stmt->execute([$pidx]);
                $payment = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Render failure UI
                $statusMessages = [
                    'User canceled' => 'You have canceled the payment process.',
                    'Expired' => 'The payment session has expired.',
                    'Failed' => 'The payment could not be processed.',
                ];
                
                $message = $statusMessages[$status] ?? "Payment was $status.";
                
                renderPaymentResult(
                    false,
                    'Payment ' . ucfirst($status),
                    $message . ' Please try again or contact support if you continue to experience issues.',
                    [
                                                'Order ID' => $purchase_order_id,
                        'Status' => ucfirst($status),
                        'Product' => $payment['product_name'] ?? 'N/A',
                        'Amount' => 'NPR ' . number_format(($payment['amount'] ?? 0) / 100, 2),
                        'Date' => date('M d, Y h:i A')
                    ]
                );
            }
        } catch (Exception $e) {
            // Render error UI
            renderPaymentResult(
                false,
                'Payment Error',
                'An error occurred while processing your payment: ' . $e->getMessage(),
                [
                    'Order ID' => $purchase_order_id ?: 'N/A',
                    'Error' => $e->getMessage(),
                    'Date' => date('M d, Y h:i A')
                ]
            );
        }
        break;
         case 'verify':
        // POST /khalti-payment.php?action=verify
        // Required: pidx or order_id
        
        try {
            $pidx = $_POST['pidx'] ?? '';
            $order_id = $_POST['order_id'] ?? '';
            
            if (empty($pidx) && empty($order_id)) {
                throw new Exception("Either 'pidx' or 'order_id' is required");
            }
            
            // Get pidx from order_id if pidx not provided
            if (empty($pidx) && !empty($order_id)) {
                $stmt = $pdo->prepare("SELECT pidx FROM payments WHERE purchase_order_id = ?");
                $stmt->execute([$order_id]);
                $payment = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$payment || !$payment['pidx']) {
                    throw new Exception("Payment not found or not initiated");
                }
                $pidx = $payment['pidx'];
            }
             // Lookup payment status
            $lookup_data = ['pidx' => $pidx];
            $lookup_response = khaltiApiRequest($khalti_api_url . 'epayment/lookup/', $lookup_data, $khalti_secret_key);
            
            // Update payment status in database
            $new_status = strtolower(str_replace(' ', '_', $lookup_response['status']));
            $stmt = $pdo->prepare("UPDATE payments SET status = ?, transaction_id = ?, updated_at = NOW() WHERE pidx = ?");
            $stmt->execute([$new_status, $lookup_response['transaction_id'] ?? null, $pidx]);
            
            sendJsonResponse([
                'success' => true,
                'pidx' => $pidx,
                'order_id' => $order_id,
                'status' => $lookup_response['status'],
                'transaction_id' => $lookup_response['transaction_id'],
                'amount' => $lookup_response['total_amount'],
                'fee' => $lookup_response['fee'],
                'refunded' => $lookup_response['refunded']
            ]);
            
        } catch (Exception $e) {
            sendJsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
             }
        break;

    case 'status':
        // GET /khalti-payment.php?action=status&order_id=ORDER_123
        // Get payment status from database
        
        try {
            $order_id = $_GET['order_id'] ?? '';
            
            if (empty($order_id)) {
                throw new Exception("'order_id' parameter is required");
            }
            
            $stmt = $pdo->prepare("SELECT * FROM payments WHERE purchase_order_id = ?");
            $stmt->execute([$order_id]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$payment) {
                throw new Exception("Payment not found");
            }
              sendJsonResponse([
                'success' => true,
                'order_id' => $payment['purchase_order_id'],
                'pidx' => $payment['pidx'],
                'transaction_id' => $payment['transaction_id'],
                'amount' => $payment['amount'],
                'status' => $payment['status'],
                'customer_name' => $payment['customer_name'],
                'customer_email' => $payment['customer_email'],
                'customer_phone' => $payment['customer_phone'],
                'product_name' => $payment['product_name'],
                'created_at' => $payment['created_at'],
                'updated_at' => $payment['updated_at']
            ]);
            
        } catch (Exception $e) {
            sendJsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
        break;
         case 'list':
        // GET /khalti-payment.php?action=list&limit=10&offset=0
        // List all payments with pagination
        
        try {
            $limit = (int)($_GET['limit'] ?? 10);
            $offset = (int)($_GET['offset'] ?? 0);
            $status = $_GET['status'] ?? '';
            
            $query = "SELECT * FROM payments";
            $params = [];
            
            if (!empty($status)) {
                $query .= " WHERE status = ?";
                $params[] = $status;
            }
            
            $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Get total count
            $count_query = "SELECT COUNT(*) as total FROM payments";
            if (!empty($status)) {
                $count_query .= " WHERE status = ?";
                $count_stmt = $pdo->prepare($count_query);
                $count_stmt->execute([$status]);
            } else {
                $count_stmt = $pdo->prepare($count_query);
                $count_stmt->execute();
            }
            $total = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            sendJsonResponse([
                'success' => true,
                'payments' => $payments,
                'pagination' => [
                    'total' => (int)$total,
                    'limit' => $limit,
                    'offset' => $offset,
                    'has_more' => ($offset + $limit) < $total
                ]
            ]);
               } catch (Exception $e) {
            sendJsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
        break;

    case 'sample':
        // GET /khalti-payment.php?action=sample
        // Generate sample payment data for testing
        
        $sample_data = [
            'initiate_payment' => [
                'method' => 'POST',
                'url' => $website_url . '?action=initiate',
                'data' => [
                    'amount' => 100, // NPR amount
                    'product_name' => 'Sample Product',
                    'customer_name' => 'Test Bahadur',
                    'customer_email' => 'test@example.com',
                    'customer_phone' => '9800000001'
                ]
            ],
              'verify_payment' => [
                'method' => 'POST',
                'url' => $website_url . '?action=verify',
                'data' => [
                    'pidx' => 'HT6o6PEZRWFJ5ygavzHWd5' // OR order_id
                ]
            ],
            'check_status' => [
                'method' => 'GET',
                'url' => $website_url . '?action=status&order_id=ORDER_123'
            ],
            'list_payments' => [
                'method' => 'GET',
                'url' => $website_url . '?action=list&limit=10&offset=0&status=completed'
            ],
            'test_credentials' => [
                'khalti_id' => ['9800000000', '9800000001', '9800000002', '9800000003', '9800000004', '9800000005'],
                'mpin' => '1111',
                'otp' => '987654'
            ]
        ];
          sendJsonResponse($sample_data);
        break;

    default:
        sendJsonResponse([
            'success' => false,
            'error' => 'Invalid action',
            'available_actions' => ['initiate', 'callback', 'verify', 'status', 'list', 'sample'],
            'usage' => [
                'initiate' => 'POST ?action=initiate (amount, product_name, customer_name, customer_email, customer_phone)',
                'callback' => 'GET ?action=callback (handled automatically by Khalti)',
                'verify' => 'POST ?action=verify (pidx or order_id)',
                'status' => 'GET ?action=status&order_id=ORDER_123',
                'list' => 'GET ?action=list&limit=10&offset=0&status=completed',
                'sample' => 'GET ?action=sample (get sample request data)'
            ]
        ], 400);
   break;
}
?>



            




            












    







