<?php
session_start();

require_once 'db_connect.php';
if (isset($_SESSION['users_id'])) {
    header("Location: dashboard.php");
    exit();
}
$login_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT users_id, username, email, password_hash, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {

            $_SESSION['users_id'] = $user['users_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'customer') {
                $stmt_customer = $conn->prepare("SELECT customer_id, first_name, sur_name, loyalty_points FROM customer WHERE users_id = ?");
                $stmt_customer->bind_param("i", $user['users_id']);
                $stmt_customer->execute();
                $customer_result = $stmt_customer->get_result();
                if ($customer_result->num_rows == 1) {
                    $customer_data = $customer_result->fetch_assoc();
                    $_SESSION['customer_id'] = $customer_data['customer_id'];
                    $_SESSION['first_name'] = $customer_data['first_name'];
                    $_SESSION['sur_name'] = $customer_data['sur_name'];
                    $_SESSION['loyalty_points'] = $customer_data['loyalty_points'];
                }
                $stmt_customer->close();
            } elseif ($user['role'] == 'employee') {
                $stmt_employee = $conn->prepare("SELECT employee_id, first_name, sur_name, hourly_rate FROM employee WHERE users_id = ?");
                $stmt_employee->bind_param("i", $user['users_id']);
                $stmt_employee->execute();
                $employee_result = $stmt_employee->get_result();
                if ($employee_result->num_rows == 1) {
                    $employee_data = $employee_result->fetch_assoc();
                    $_SESSION['employee_id'] = $employee_data['employee_id'];
                    $_SESSION['first_name'] = $employee_data['first_name'];
                    $_SESSION['sur_name'] = $employee_data['sur_name'];
                    $_SESSION['employee_role'] = 'employee'; 
                    $_SESSION['hourly_rate'] = $employee_data['hourly_rate'];
                }
                $stmt_employee->close();
            }
            if ($user['role'] == 'employee') {
                header("Location:admin_dashboard.php");  
            } elseif ($user['role'] == 'customer') {
                header("Location:dashboard.php");
            } else {

                header("Location:dashboard.php");
            }
            exit();
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "User not found.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Co. Login</title>
    <link rel="stylesheet" href="01.css">
</head>
<body>
    <div class="login-form">
        <img src="logo.png" alt="Coffee Co. Logo" class="logo">
        <h2>Login to Coffee Co.</h2>

        <?php if ($login_error): ?>
            <p class="error"><?php echo $login_error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username_or_email" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Don't have an Account ? <a href="registration.php">Register</a></p>
        </form>
    </div>
</body>

</html>