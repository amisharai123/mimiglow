<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.php");
    exit();
}

include('../db_connection.php');

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    mysqli_begin_transaction($conn);
    
    try {
        mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $order_id");
        mysqli_query($conn, "DELETE FROM orders WHERE order_id = $order_id");
        mysqli_commit($conn);
        $_SESSION['message'] = "Order #$order_id has been deleted.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['message'] = "Failed to delete order: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

$total_query = "SELECT COUNT(*) as total FROM orders WHERE status = 'completed'";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $records_per_page);

// Order + user query
$orders_query = "SELECT o.order_id, o.created_at, u.full_name, u.email, u.phone 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 WHERE o.status = 'completed' 
                 ORDER BY o.created_at DESC 
                 LIMIT $offset, $records_per_page";
$orders_result = mysqli_query($conn, $orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mimi Glow - Admin Orders</title>
    <link rel="stylesheet" href="admin_dash.css?v=<?php echo time(); ?>">
    <style>
        /* Minimal tweaks for Mimi Glow aesthetic */
        body {
            font-family:'Times New Roman', Times, serif;
            background-color: #fff8f4;
        }
        .orders-container {
            padding: 20px;
        }
        .order-card {
            background: #fff;
            border-left: 5px solid #e91e63;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .order-detail-btn, .delete-btn {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .order-detail-btn {
            background: #e91e63;
            color: white;
        }
        .delete-btn {
            background: #f44336;
            color: white;
            margin-left: 5px;
        }
        .pagination a {
            padding: 6px 12px;
            background: #f8bbd0;
            border-radius: 4px;
            color: #000;
            text-decoration: none;
            margin: 2px;
        }
        .pagination a.active {
            background: #e91e63;
            color: white;
        }
        .modal { /* You can copy your modal CSS from before */ }
    </style>
</head>
<body>

<div id="header-placeholder"></div>
<script>
    fetch('../Admin_Page/admin_header.php')
        .then(response => response.text())
        .then(data => document.getElementById('header-placeholder').innerHTML = data);
</script>

<div class="orders-container">
    <h1>Manage Orders - Mimi Glow</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message'], $_SESSION['message_type']);
            ?>
        </div>
    <?php endif; ?>

    <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <strong>Order #<?php echo $order['order_id']; ?></strong><br>
                    <small><?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></small>
                </div>
                <div>
                    <button class="order-detail-btn" onclick="toggleOrderDetails(<?php echo $order['order_id']; ?>)">Details</button>
                    <button class="delete-btn" onclick="showDeleteConfirmation(<?php echo $order['order_id']; ?>)">Delete</button>
                </div>
            </div>
            <div><strong>Name:</strong> <?php echo $order['full_name']; ?></div>
            <div><strong>Email:</strong> <?php echo $order['email']; ?></div>
            <div><strong>Phone:</strong> <?php echo $order['phone']; ?></div>

            <div id="order-details-<?php echo $order['order_id']; ?>" style="display: none; margin-top: 10px;">
                <table width="100%" border="1" cellpadding="8" style="border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $item_query = "SELECT oi.*, p.product_name, p.image_url FROM order_items oi 
                                       JOIN products p ON oi.product_id = p.product_id 
                                       WHERE order_id = {$order['order_id']}";
                        $item_result = mysqli_query($conn, $item_query);
                        $total = 0;
                        while ($item = mysqli_fetch_assoc($item_result)):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <img src="../Admin_Page/uploads/<?php echo $item['image_url']; ?>" width="50" style="vertical-align: middle;">
                                <?php echo $item['product_name']; ?>
                            </td>
                            <td>Rs.<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rs.<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="right"><strong>Total:</strong></td>
                            <td><strong>Rs.<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php endwhile; ?>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<!-- Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this order?</p>
        <form method="post" id="deleteForm">
            <input type="hidden" name="order_id" id="deleteOrderId">
            <button type="submit" name="delete_order" class="confirm-delete">Yes, Delete</button>
            <button type="button" class="cancel-delete" onclick="closeDeleteModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function toggleOrderDetails(id) {
    const section = document.getElementById(`order-details-${id}`);
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}
function showDeleteConfirmation(id) {
    document.getElementById('deleteOrderId').value = id;
    document.getElementById('deleteModal').style.display = 'block';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
window.onclick = function(e) {
    if (e.target == document.getElementById('deleteModal')) {
        closeDeleteModal();
    }
}
</script>

</body>
</html>
