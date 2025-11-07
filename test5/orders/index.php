<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CoffeeCo</title>
  <link rel="stylesheet" href="order.css" />
</head>
<body>
  <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>CoffeeCo</h1>
        </div>
    <div class="nav-links">
        <?php
          $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'customer';
          $dashboardLink = ($role === 'employee') ? '../admin_dashboard.php' :'../dashboard.php' ;
        ?>
        <a href="<?php echo $dashboardLink; ?>">Home</a>
    </div>
        <div class="cart-icon-container">
          <button id="openCart">🛒 <span id="cartCount">0</span></button>
      </div>
  </div>

  <div class="background-overlay"></div>

  <div class="pos-container">
    <header>
      <img src="logo.png" alt="Coffee Logo" class="logo">
      <h1>CoffeeCo</h1>
    </header>

    <main>
      <section class="menu">
        <h2>Menu</h2>
        <div class="menu-items" id="menuItems"></div>
      </section>


    <div class="cart">
      <h2>Cart</h2>
      <ul id="cartList"></ul>
      <div class="cart-footer">
        <p>Total: <span id="totalPrice">₱0</span></p>
        <div class="cart-buttons">
          <button id="clearCart">Clear Cart</button>
          <button id="checkoutBtn">Checkout</button>
          <button id="closeCart">Close Cart</button>
        </div>
      </div>
    </div>

  <script src="script.js"></script>
</body>
</html>
