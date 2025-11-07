<?php
session_start();
require_once '../db_connect.php';

if ($_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['users_id'])) {
    $users_id = $_POST['users_id'];

    if ($users_id == $_SESSION['users_id']) {
        echo "You cannot delete your own account.";
        exit();
    }

    $stmt = $conn->prepare("SELECT role FROM users WHERE users_id = ?");
    $stmt->bind_param("i", $users_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $role = $user['role'];


        if ($role === 'customer') {
            $stmt = $conn->prepare("DELETE FROM customer WHERE users_id = ?");
            $stmt->bind_param("i", $users_id);
            $stmt->execute();
            $stmt->close();
        }

        if ($role === 'employee') {
            $stmt = $conn->prepare("DELETE FROM employee WHERE users_id = ?");
            $stmt->bind_param("i", $users_id);
            $stmt->execute();
            $stmt->close();
        }


        $stmt = $conn->prepare("DELETE FROM users WHERE users_id = ?");
        $stmt->bind_param("i", $users_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: /user/management_user.php");
exit();
?>
