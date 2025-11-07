<?php
session_start();
require_once '../db_connect.php';


if (!isset($_GET['order_id'])) {
    die("No order ID provided.");
}
$order_id = intval($_GET['order_id']);


$stmt = $conn->prepare("
    SELECT o.orders_id, o.total_amount, o.status, 
           oi.product_id, oi.quantity, oi.item_price, p.name
    FROM orders o
    JOIN order_items oi ON o.orders_id = oi.orders_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE o.orders_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$orderItems = [];
$totalAmount = 0;
$status = "";
while ($row = $result->fetch_assoc()) {
    $orderItems[] = $row;
    $totalAmount = $row['total_amount'];
    $status = $row['status'];
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];


    $stmt = $conn->prepare("INSERT INTO payment (orders_id, payment_date, payment_method, amount, payment_status) 
                            VALUES (?, NOW(), ?, ?, 'Completed')");
    $stmt->bind_param("isd", $order_id, $payment_method, $amount);
    $stmt->execute();
    $stmt->close();


    $stmt = $conn->prepare("UPDATE orders SET status = 'Paid' WHERE orders_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();


    $redirect = "../dashboard.php";

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee') {
        $redirect = "../admin_dashboard.php";
    }

    echo "<script>
            alert('✅ Payment successful!');
            window.location.href = '{$redirect}';
          </script>";
  header("Location: receipt.php?order_id=$order_id");
exit;


    
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment - CoffeeCo</title>
  <link rel="stylesheet" href="01.css">
</head>
<body>
  <h1>Payment for Order #<?php echo $order_id; ?></h1>

  <h2>Order Summary</h2>
  <ul>
    <?php foreach ($orderItems as $item): ?>
      <li>
        <?php echo htmlspecialchars($item['name']); ?> 
        x <?php echo $item['quantity']; ?> = ₱<?php echo $item['item_price'] * $item['quantity']; ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <p><strong>Total: ₱<?php echo $totalAmount; ?></strong></p>



  <form method="POST">
    <label for="payment_method">Payment Method:</label>
    <select name="payment_method" id="payment_method" required>
      <option value="Cash">Cash</option>
      <option value="Credit Card">Credit Card</option>
      <option value="GCash">GCash</option>
    </select>
    <br><br>

    <label for="amount">Amount:</label>
    <input type="number" step="0.01" name="amount" id="amount" value="<?php echo $totalAmount; ?>" required>
    <br><br>

    <button type="submit">Pay Now</button>
  </form>

      

  <?php
    $redirect = "../orders/index.php";
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee') {
        $redirect = "../admin_dashboard.php";
    }
  ?>
  <a href="<?php echo $redirect; ?>" class="back-btn">← Back to Menu</a>

</body>
</html>
