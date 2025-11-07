<?php
session_start();
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status   = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE orders_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: management_orders.php");
exit();
?>