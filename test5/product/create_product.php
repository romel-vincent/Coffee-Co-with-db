<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);

     $supplier_id = 1;
    if (!empty($name) && $price >= 0 && !empty($category)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, supplier_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $name, $description, $price, $category, $supplier_id);

        if ($stmt->execute()) {
            header("Location:management_product.php?success=product_created");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product | Coffee Co.</title>
    <link rel="stylesheet" href="creat.css">
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
        <h2>Create New Product</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <label for="name">Product Name *</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>

            <label for="price">Price (₱) *</label>
            <input type="number" step="0.01" name="price" id="price" required>

            <label for="category">Category *</label>
            <select name="category" id="category" required>
                <option value="">-- Select Category --</option>
                <option value="Beverage">Beverage</option>
                <option value="Pastry">Pastry</option>
                <option value="Sandwich">Sandwich</option>
                <option value="Dessert">Dessert</option>
            </select>

            <button type="submit">Create Product</button>
        </form>
    </div>
</body>
</html>
