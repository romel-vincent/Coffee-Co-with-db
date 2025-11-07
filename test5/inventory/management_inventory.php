<?php
session_start();

if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

$sql = "SELECT * FROM product_inventory_status ORDER BY name ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="int.css">
</head>
<body>
    <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>Inventory Management</h1>
        </div>
        <div class="nav-links">
            <a href="../admin_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    <div id="inventoryTable" class="user-table">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Reorder Level</th>
                    <th>Availability</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr><td colspan="6">No inventory records found.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $availabilityText = $row['availability'] ? 'Available' : 'Unavailable';
                            $rowClass = ($row['quantity_in_stock'] <= $row['reorder_lvl']) ? 'low-stock' : '';
                        ?>
                        <tr>
                            <td class="<?= $rowClass ?>"><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['quantity_in_stock'] ?></td>
                            <td><?= $row['reorder_lvl'] ?></td>
                            <td><?= $availabilityText ?></td>
                            <td><?= $row['last_update'] ?></td>
                            <td>
                                <form method="POST" action="update_stock.php" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                    <input type="number" name="adjust_qty" placeholder="±Qty" required>
                                    <input type="number" name="reorder_lvl" placeholder="Reorder Level" value="<?= $row['reorder_lvl'] ?>">
                                    <button type="submit" class="edit-btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php $conn->close(); ?>
</body>
</html>
