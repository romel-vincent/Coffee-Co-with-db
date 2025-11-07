<?php
session_start();
require_once '../db_connect.php';

if ($_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}

$create_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $first_name = trim($_POST['first_name'] ?? '');
    $sur_name   = trim($_POST['sur_name'] ?? '');
    $hourly_rate = isset($_POST['hourly_rate']) ? floatval($_POST['hourly_rate']) : null;

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $create_error = "All fields are required.";
    } else {
        $check = $conn->prepare("SELECT users_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $create_error = "This email is already registered. Please use another one.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password_hash, $role);

            if ($stmt->execute()) {
                $new_user_id = $stmt->insert_id;

                if ($role === 'customer') {
                    $insert = $conn->prepare("INSERT INTO customer (users_id, first_name, sur_name, email) VALUES (?, ?, ?, ?)");
                    $insert->bind_param("isss", $new_user_id, $first_name, $sur_name, $email);
                    $insert->execute();
                    $insert->close();
                }

                if ($role === 'employee') {
                    $insert = $conn->prepare("INSERT INTO employee (users_id, first_name, sur_name, email, hourly_rate) VALUES (?, ?, ?, ?, ?)");
                    $insert->bind_param("isssd", $new_user_id, $first_name, $sur_name, $email, $hourly_rate);
                    $insert->execute();
                    $insert->close();
                }

                header("Location: /test2/admin_dashboard.php");
                exit();
            } else {
                $create_error = "Failed to create user.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User | Coffee Co.</title>
    <link rel="stylesheet" href="create.css">
</head>
<body>

    <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>Edit User</h1>
        </div>
        <div class="nav-links">
            <a href="../admin_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="create-container">
        <h2>Create New User</h2>

        <?php if ($create_error): ?>
            <div class="error-message"><?php echo $create_error; ?></div>
        <?php endif; ?>

        <form class="create-form" method="POST" action="">
            <label>First Name:</label>
            <input type="text" name="first_name" placeholder="Enter first name" required>

            <label>Surname:</label>
            <input type="text" name="sur_name" placeholder="Enter surname" required>

            <label>Username:</label>
            <input type="text" name="username" placeholder="Enter username" required>

            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter email address" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <label>Role:</label>
            <select name="role" id="role" required onchange="toggleHourlyRate()">
                <option value="customer">Customer</option>
                <option value="employee">Employee</option>
            </select>

            <div id="hourlyRateField" style="display: none;">
                <label>Hourly Rate:</label>
                <input type="number" name="hourly_rate" step="0.01" placeholder="Enter hourly rate">
            </div>

            <div class="form-buttons">
                <button type="submit" class="create-btn">Create</button>
                <a href="/test2/admin_dashboard.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function toggleHourlyRate() {
            const roleSelect = document.getElementById("role");
            const rateField = document.getElementById("hourlyRateField");
            rateField.style.display = roleSelect.value === "employee" ? "block" : "none";
        }
    </script>

</body>
</html>
