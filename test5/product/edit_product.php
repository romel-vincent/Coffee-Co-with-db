<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);


    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        die("Product not found.");
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $product_id = intval($_POST['product_id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, category=? WHERE product_id=?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $category, $product_id);

    if ($stmt->execute()) {
        header("Location: management_product.php?success=product_updated");
        exit();
    } else {
        $error = "Error updating product: " . $stmt->error;
    }
    $stmt->close();
} else {
    die("Invalid request.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Coffee Co.</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

    <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>Coffee Co. Admin</h1>
        </div>
        <div class="nav-links">
            <a href="../admin_dashboard.php">Dashboard</a>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
    </div>

  
    <div class="form-wrapper">
        <h2>Edit Product</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">

            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($product['description']); ?></textarea>

            <label>Price (₱)</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price']; ?>" required>

            <label>Category</label>
            <input type="text" name="category" value="<?= htmlspecialchars($product['category']); ?>" required>

            <div class="form-buttons">
                <button type="submit" name="update" class="update-btn">Update Product</button>
                <a href="management_product.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
