<?php
session_start();
if (!isset($_SESSION['users_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../db_connect.php';

$result = $conn->query("SELECT users_id, username, email, role FROM users ORDER BY users_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Coffee Co.</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="navbar">
        <div class="brand">
            <img src="../logo.png" alt="CoffeeDB Logo" class="logo">
            <h1>User Management</h1>
        </div>
        <div class="nav-links">
            <a href="../admin_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

  
    <div id="userTable" class="user-table">
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td>
                            <div style="display: inline-flex; gap: 8px;">
                                <form method="POST" action="edit_user.php">
                                    <input type="hidden" name="users_id" value="<?= $row['users_id'] ?>">
                                    <button type="submit" class="edit-btn">Edit</button>
                                </form>
                                <form method="POST" action="delete_user.php" onsubmit="return confirm('Delete this user?')">
                                    <input type="hidden" name="users_id" value="<?= $row['users_id'] ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </div>
                        </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

 
    <div id="createUserBtn" class="create-user-btn">
        <a href="create_user.php">+ Create New User</a>
    </div>

    <?php $conn->close(); ?>

    <script>

        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
