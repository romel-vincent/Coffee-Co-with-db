<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: login.php");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dash.css?v=1.2">
    <title>Dashboard</title>
</head>
<body>
    <div class="navbar">
        <div class="brand">
            <img src="orders/logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>CoffeeCo</h1>
        </div>
        <div class="nav-links">
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="content">
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

            <?php if ($_SESSION['role'] == 'customer'): ?>
                <a href="customer_order/order.php" class="order-btn">Order Now</a>
            <?php elseif ($_SESSION['role'] == 'employee'): ?>
                <p>Employee ID: <?php echo isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : 'Not set'; ?></p>
                <p>Name: <?php echo (isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'Not set') . " " . (isset($_SESSION['sur_name']) ? $_SESSION['sur_name'] : ''); ?></p>
                <p>Employee Role: <?php echo isset($_SESSION['employee_role']) ? $_SESSION['employee_role'] : 'Not set'; ?></p>
            <?php endif; ?>
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
