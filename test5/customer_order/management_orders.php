<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

$sql = "
    SELECT o.orders_id AS order_id,
           o.total_amount,
           o.order_date,
           o.status,
           oi.quantity,
           oi.item_price,
           p.name AS product_name
    FROM orders o
    JOIN order_items oi ON o.orders_id = oi.orders_id
    JOIN products p ON oi.product_id = p.product_id
    ORDER BY o.order_date DESC, o.orders_id DESC
";


$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}


$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']]['info'] = [
        'total_amount' => $row['total_amount'],
        'order_date'   => $row['order_date'],
        'status'       => $row['status']
    ];
    $orders[$row['order_id']]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity'     => $row['quantity'],
        'price'        => $row['item_price']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <link rel="stylesheet" href="manage.css">
    
</head>
<body>
    <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>Order Management</h1>
        </div>
        <div class="nav-links">
            <a href="../admin_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="orders-table">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total (₱)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order_id => $order): ?>
                <tr>
                    <td><?= $order_id ?></td>
                    <td><?= $order['info']['order_date'] ?></td>
                    <td><?= htmlspecialchars($order['info']['status']) ?></td>
                    <td>₱<?= number_format($order['info']['total_amount'], 2) ?></td>
                    <td>
                        <form method="POST" action="update_orders.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                            <select name="status">
                                <option value="Pending"   <?= $order['info']['status']=='Pending'?'selected':'' ?>>Pending</option>
                                <option value="Completed" <?= $order['info']['status']=='Completed'?'selected':'' ?>>Completed</option>
                                <option value="Cancelled" <?= $order['info']['status']=='Cancelled'?'selected':'' ?>>Cancelled</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                        <form method="POST" action="delete_orders.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                            <button type="submit" onclick="return confirm('Delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
<script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</html>
<?php $conn->close(); ?>
