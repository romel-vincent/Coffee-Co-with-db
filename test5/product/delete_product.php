<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("Location: management_product.php?success=product_deleted");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
