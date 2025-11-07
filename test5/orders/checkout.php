<?php
session_start();
require_once '../db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['total_amount'], $data['items'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$customer_id = $_SESSION['customer_id'] ?? null;
$employee_id = $_SESSION['employee_id'] ?? null;
$total_amount = $data['total_amount'];
$items = $data['items'];

$conn->begin_transaction();

try {

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, employee_id, total_amount, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("iid", $customer_id, $employee_id, $total_amount);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO order_items (orders_id, product_id, quantity, item_price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['qty'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    $conn->commit();
    echo json_encode(["success" => true, "order_id" => $order_id]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Database error"]);
}