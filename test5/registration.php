<?php
require_once 'db_connect.php';
$registration_error = '';
$registration_success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $first_name = trim($_POST['first_name']);
    $sur_name = trim($_POST['sur_name']);


    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $registration_error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registration_error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $registration_error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $registration_error = "Password must be at least 6 characters long.";
    } elseif (!in_array($role, ['employee', 'customer'])) {
        $registration_error = "Invalid role selected.";
    } else {
        $stmt_check = $conn->prepare("SELECT users_id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            $registration_error = "Username or email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("ssss", $username, $email, $password_hash, $role);
            if ($stmt_insert->execute()) {
                $users_id = $stmt_insert->insert_id;
                if ($role === 'customer') {
                    $stmt_customer = $conn->prepare(
                        "INSERT INTO customer (users_id, first_name, sur_name, email) VALUES (?, ?, ?, ?)");
                    $stmt_customer->bind_param("isss", $users_id, $first_name, $sur_name, $email);
                    $stmt_customer->execute();
                    $stmt_customer->close();
                } elseif ($role === 'employee') {
                    $stmt_employee = $conn->prepare(
                        "INSERT INTO employee (users_id, first_name, sur_name, email) VALUES (?, ?, ?, ?)");
                    $stmt_employee->bind_param("isss", $users_id, $first_name, $sur_name, $email);
                    $stmt_employee->execute();
                    $stmt_employee->close();
                }


                $registration_success = "Registration successful! You can now <a href='login.php'>login</a>.";

            } else {
                $registration_error = "Registration failed. Please try again.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }

}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoffeeDB Registration</title>
    <link rel="stylesheet" href="reg.css">
    </head>
<body>
    <div class="registration-form">
        <img src="logo.png" alt="Coffee Co. Logo" class="logo">
        <h2>Create an Account</h2>
        <?php if ($registration_error): ?>
            <p class="error"><?php echo $registration_error; ?></p>
        <?php endif; ?>
        <?php if ($registration_success): ?>
            <p class="success"><?php echo $registration_success; ?></p>
        <?php else: ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="sur_name" placeholder="Surname" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="customer">Customer</option>
                </select>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>
