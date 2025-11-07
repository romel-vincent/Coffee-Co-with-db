<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

// Redirect if not employee
if ($_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}

// Handle form submission
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST['users_id'], $_POST['username'], $_POST['email'], $_POST['role'])){
    $users_id = $_POST['users_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $first_name = trim($_POST['first_name']);
    $sur_name = trim($_POST['sur_name']);
    $hourly_rate = isset($_POST['hourly_rate']) ? floatval($_POST['hourly_rate']) : null;


    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE users_id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $users_id);


if ($stmt->execute()) {
        // ✅ Role-switch cleanup
        if ($role === 'employee') {
        $delete = $conn->prepare("DELETE FROM customer WHERE users_id = ?");
        $delete->bind_param("i", $users_id);
        $delete->execute();
        $delete->close();
    }

    if ($role === 'customer') {
        $delete = $conn->prepare("DELETE FROM employee WHERE users_id = ?");
        $delete->bind_param("i", $users_id);
        $delete->execute();
        $delete->close();
    }


    // Role-based inserts AFTER update
    if ($role === 'customer') {
        $check = $conn->prepare("SELECT * FROM customer WHERE users_id = ?");
        $check->bind_param("i", $users_id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows === 0) {
            $insert = $conn->prepare("INSERT INTO customer (users_id, first_name, sur_name, email) VALUES (?, ?, ?, ?)");
            $insert->bind_param("isss", $users_id, $first_name, $sur_name, $email);
            $insert->execute();
            $insert->close();
        } else {
        // Update existing customer row
        $update = $conn->prepare("UPDATE customer SET first_name = ?, sur_name = ?, email = ? WHERE users_id = ?");
        $update->bind_param("sssi", $first_name, $sur_name, $email, $users_id);
        $update->execute();
        $update->close();
    }

        $check->close();
    }

    if ($role === 'employee') {
        $check = $conn->prepare("SELECT * FROM employee WHERE users_id = ?");
        $check->bind_param("i", $users_id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows === 0) {
            $insert = $conn->prepare("INSERT INTO employee (users_id, first_name, sur_name, email, hourly_rate) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("isssd", $users_id, $first_name, $sur_name, $email, $hourly_rate);
            $insert->execute();
            $insert->close();      
        } else {
            $update = $conn->prepare("UPDATE employee SET first_name = ?, sur_name = ?, email = ?, hourly_rate = ? WHERE users_id = ?");
            $update->bind_param("sssdi", $first_name, $sur_name, $email, $hourly_rate, $users_id);
            $update->execute();
            $update->close();
}

        $check->close();
    }

    header("Location:/test2/admin_dashboard.php");
    exit();
} else {
    echo "Update failed.";
}
$stmt->close();
}

// Load user data
if (isset($_POST['users_id'])) {
    $users_id = $_POST['users_id'];
    $stmt = $conn->prepare("SELECT username, email, role FROM users WHERE users_id = ?");
    $stmt->bind_param("i", $users_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

        if ($user['role'] === 'employee') {
        $stmt = $conn->prepare("SELECT first_name, sur_name, hourly_rate FROM employee WHERE users_id = ?");
        $stmt->bind_param("i", $users_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
            if ($details) {
                $user['first_name'] = $details['first_name'];
                $user['sur_name'] = $details['sur_name'];
                $user['hourly_rate'] = $details['hourly_rate'];
            }
        $stmt->close();
    }


        if ($user['role'] === 'customer') {
        $stmt = $conn->prepare("SELECT first_name, sur_name FROM customer WHERE users_id = ?");
        $stmt->bind_param("i", $users_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
            if ($details) {
                $user['first_name'] = $details['first_name'];
                $user['sur_name'] = $details['sur_name'];
            }
        $stmt->close();
    }



} else {
    echo "No user selected.";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | Coffee Co.</title>
    <link rel="stylesheet" href="edit.css">
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

    <div class="edit-container">
        <form method="POST" action="" class="edit-form">
            <input type="hidden" name="users_id" value="<?php echo $users_id; ?>">

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo isset($user['first_name']) ? htmlspecialchars($user['first_name']) : ''; ?>" required>

            <label>Surname:</label>
            <input type="text" name="sur_name" value="<?php echo isset($user['sur_name']) ? htmlspecialchars($user['sur_name']) : ''; ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="customer" <?php if ($user['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                <option value="employee" <?php if ($user['role'] == 'employee') echo 'selected'; ?>>Employee</option>
            </select>

            <div id="hourlyRateContainer" style="display: <?php echo ($user['role'] === 'employee') ? 'block' : 'none'; ?>;">
                <label>Hourly Rate:</label>
                <input type="number" step="0.01" name="hourly_rate" 
                       value="<?php echo ($user['role'] === 'employee') ? htmlspecialchars($user['hourly_rate']) : ''; ?>">
            </div>

            <div class="form-buttons">
                <button type="submit" class="update-btn">Update</button>
                <a href="../admin_dashboard.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Toggle hourly rate field visibility
        document.querySelector('select[name="role"]').addEventListener('change', function() {
            const rateContainer = document.getElementById('hourlyRateContainer');
            rateContainer.style.display = this.value === 'employee' ? 'block' : 'none';
        });
    </script>
</body>
</html>
