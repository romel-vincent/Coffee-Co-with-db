<?php
require_once '../db_connect.php';

$result = $conn->query("
    SELECT product_id, name, price, category, availability
    FROM product_inventory_status
    WHERE availability = 1
    ORDER BY category, name
");

$menu = [];
while ($row = $result->fetch_assoc()) {
    $menu[] = [
        "product_id" => $row['product_id'],
        "name" => $row['name'],
        "price" => (float)$row['price'],
        "category" => $row['category']
    ];
}

header('Content-Type: application/json');
echo json_encode($menu);
?>
