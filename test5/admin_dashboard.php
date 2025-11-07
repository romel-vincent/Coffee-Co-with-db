<?php
session_start();
if ($_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

require_once 'db_connect.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Co. Admin Dashboard</title>
    <link rel="stylesheet" href="admind.css">
</head>
<body>
    <div class="navbar">
        <div class="brand">
            <img src="logo.png" alt="Coffee Co. Logo" class="logo">
            <h1>Coffee Co. Admin Dashboard</h1>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="background-overlay"></div>

    <div class="content">
        <h2>Welcome, Admin ☕</h2>
        <p>Select a section to manage:</p>
        
        <div class="dashboard-links">
            <a href="orders/index.php" class="clickable-box">💻 Check Menu</a>
            <a href="user/management_user.php" class="clickable-box">👤 User Management</a>
            <a href="orders/management_orders.php" class="clickable-box">📋 Order Management</a>
            <a href="orders/sales.php" class="clickable-box">📋 Sales</a>
            <a href="product/management_product.php" class="clickable-box">☕ Product Management</a>
            <a href="inventory/management_inventory.php" class="clickable-box">📦 Inventory Management</a>
            <a href="supplier/supplier_information.php" class="clickable-box">🧑‍✈️ Supplier Information</a>
        </div>
    </div>
</body>
</html>


    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>