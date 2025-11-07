<?php
session_start();

if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $adjust_qty = intval($_POST['adjust_qty']);
    $reorder_lvl = isset($_POST['reorder_lvl']) ? intval($_POST['reorder_lvl']) : 0;

    $stmt = $conn->prepare("SELECT quantity_in_stock FROM inventory WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($current_stock);
    $stmt->fetch();
    $stmt->close();

    if ($current_stock === null) {

        $new_stock = max(0, $adjust_qty);
        $availability = ($new_stock > 0) ? 1 : 0;

        $stmt = $conn->prepare("INSERT INTO inventory (product_id, quantity_in_stock, reorder_lvl, availability, last_update) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiii", $product_id, $new_stock, $reorder_lvl, $availability);
        $stmt->execute();
        $stmt->close();
    } else {

        $new_stock = max(0, $current_stock + $adjust_qty);
        $availability = ($new_stock > 0) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE inventory SET quantity_in_stock = ?, reorder_lvl = ?, availability = ?, last_update = NOW() WHERE product_id = ?");
        $stmt->bind_param("iiii", $new_stock, $reorder_lvl, $availability, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: management_inventory.php?success=stock_updated");
    exit();
}
