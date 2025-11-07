<?php
session_start();
require_once '../db_connect.php';


if (!isset($_GET['order_id'])) {
    die("No order ID provided.");
}
$order_id = intval($_GET['order_id']);

$sql = "
    SELECT 
        o.orders_id, o.total_amount, o.status, o.order_date,
        pmt.payment_id, pmt.payment_date, pmt.payment_method, pmt.amount, pmt.payment_status,
        oi.quantity, oi.item_price, pr.name AS product_name
    FROM orders o
    JOIN order_items oi ON o.orders_id = oi.orders_id
    JOIN products pr ON oi.product_id = pr.product_id
    LEFT JOIN payment pmt ON o.orders_id = pmt.orders_id
    WHERE o.orders_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$orderItems = [];
$info = null;
while ($row = $result->fetch_assoc()) {
    $orderItems[] = $row;
    $info = $row; 
}
$stmt->close();

if (!$info) {
    die("Order not found.");
}


$redirect = "../dashboard.php";
if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee') {
    $redirect = "../admin_dashboard.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #<?php echo $order_id; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 30px;
            color: #333;
        }
        .receipt-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 25px;
            max-width: 600px;
            margin: auto;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .details {
            margin-top: 15px;
            line-height: 1.6;
        }
        .items {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .items th, .items td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items th {
            background: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 1.2em;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 0.9em;
            color: #555;
        }
        .print-btn, .back-btn {
            display: block;
            margin: 20px auto;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .print-btn:hover, .back-btn:hover {
            background: #45a049;
        }
        .back-btn {
            background: #2196F3;
        }
        .back-btn:hover {
            background: #1976D2;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="header">
        <h1>CoffeeCo</h1>
        <h3>Official Receipt</h3>
        <p>Order #: <?php echo $info['orders_id']; ?></p>
    </div>

    <div class="details">
        <p><strong>Order Date:</strong> <?php echo date("F j, Y g:i A", strtotime($info['order_date'])); ?></p>
        <p><strong>Payment Date:</strong> 
            <?php echo $info['payment_date'] ? date("F j, Y g:i A", strtotime($info['payment_date'])) : 'N/A'; ?>
        </p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($info['payment_method'] ?? 'N/A'); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($info['payment_status'] ?? 'Pending'); ?></p>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($item['item_price'], 2); ?></td>
                    <td>₱<?php echo number_format($item['item_price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total"><strong>Total Amount:</strong> ₱<?php echo number_format($info['total_amount'], 2); ?></p>

    <button class="print-btn" onclick="window.print()">🖨️ Print Receipt</button>

    <a href="<?php echo $redirect; ?>" class="back-btn">← Back to Menu</a>

    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p><em>This serves as your official proof of payment.</em></p>
    </div>
</div>

</body>
</html>
