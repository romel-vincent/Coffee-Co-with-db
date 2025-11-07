<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connect.php';

$supplierResult = $conn->query("SELECT name, contact_number, contact_email, company FROM supplier");

if (!$supplierResult) {
    die("Query failed: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Information</title>
    <link rel="stylesheet" href="supp.css">
</head>
<body>

    <div class="background-overlay"></div>
    <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>Supplier</h1>
        </div>
        <div class="nav-links">
            <a href="../admin_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    <div class="user-table">
    <form action="" method="post">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($supplierResult->num_rows > 0): ?>
            <?php while ($row = $supplierResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_number']) ?></td>
                    <td><?= htmlspecialchars($row['contact_email']) ?></td>
                    <td><?= htmlspecialchars($row['company']) ?></td>
                </tr>
                <?php endwhile; ?>
             <?php else: ?>
            <tr><td colspan="4">No supplier found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </form>
    </div>
    <?php $conn->close(); ?>
</body>
</html>