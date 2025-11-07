<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

$result = $conn->query("SELECT * FROM product_inventory_status ORDER BY product_id ASC");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <link rel="stylesheet" href="product.css">
</head>
<body>
    <div class="navbar">
    <div class="brand">
        <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
        <h1>Product Management</h1>
    </div>
    <div class="nav-links">
        <a href="../admin_dashboard.php">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

    <div id="productTable" class="user-table">
        <table>
            <thead>
                <tr>
                    <th>Name</th><th>Description</th><th>Price</th>
                    <th>Availability</th><th>Category</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $availabilityText = $row['availability'] ? 'Available' : 'Unavailable'; ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $availabilityText ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>
                            <form method="POST" action="edit_product.php" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" class="edit-btn">Edit</button>
                            </form>
                            <form method="POST" action="delete_product.php" style="display:inline;" 
                                onsubmit="return confirm('Delete this product?')">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>

    <div id="createProductBtn">
        <a href="create_product.php" class="btn">Create New Product</a>
    </div>

<?php $conn->close(); ?>
</body>
</html>
