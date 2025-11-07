<?php
session_start();
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);

    $conn->query("DELETE FROM order_items WHERE orders_id = $order_id");
    $conn->query("DELETE FROM orders WHERE orders_id = $order_id");
}

header("Location: management_orders.php");
exit();
?>
