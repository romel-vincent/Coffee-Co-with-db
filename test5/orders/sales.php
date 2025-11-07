<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$method = isset($_GET['method']) ? trim($_GET['method']) : '';
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';


$sql = "
    SELECT 
        payment_id,
        orders_id,
        payment_date,
        payment_method,
        amount,
        payment_status
    FROM payment
    WHERE payment_status = 'Completed'
";

$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (orders_id LIKE ? OR payment_id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

if (!empty($method)) {
    $sql .= " AND payment_method = ?";
    $params[] = $method;
    $types .= "s";
}

if (!empty($from) && !empty($to)) {
    $sql .= " AND DATE(payment_date) BETWEEN ? AND ?";
    $params[] = $from;
    $params[] = $to;
    $types .= "ss";
}

$sql .= " ORDER BY payment_date DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed Transactions</title>
    <link rel="stylesheet" href="sales.css">
</head>
<body>
<div class="container">
    <h1>Completed Transactions</h1>

    <form method="GET" class="filter-form">
        <input type="text" name="search" placeholder="Search Order or Payment ID" value="<?php echo htmlspecialchars($search); ?>">
        <select name="method">
            <option value="">All Methods</option>
            <option value="Cash" <?php if($method=='Cash') echo 'selected'; ?>>Cash</option>
            <option value="Credit Card" <?php if($method=='Credit Card') echo 'selected'; ?>>Credit Card</option>
            <option value="GCash" <?php if($method=='GCash') echo 'selected'; ?>>GCash</option>
        </select>
        <input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>">
        <input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>">
        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['payment_id']; ?></td>
                    <td><?php echo $row['orders_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                    <td>₱<?php echo number_format($row['amount'], 2); ?></td>
                    <td class="status"><?php echo htmlspecialchars($row['payment_status']); ?></td>
                    <td><?php echo date("F j, Y g:i A", strtotime($row['payment_date'])); ?></td>
                    <td>
                        <a class="view-btn" href="../payment/receipt.php?order_id=<?php echo $row['orders_id']; ?>">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No transactions found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="../admin_dashboard.php" class="btn">⬅ Back to Menu</a>
</div>
</body>
</html>
