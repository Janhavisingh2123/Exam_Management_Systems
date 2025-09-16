<?php
include("includes/auth.php");
requireAdmin();
include("includes/db_connect.php");

$message = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM users WHERE user_id=$id")) {
        $message = "<div class='alert alert-success'>✅ User deleted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error deleting user: " . $conn->error . "</div>";
    }
}

// Fetch All Users
$result = $conn->query("SELECT u.user_id, u.username, u.role, i.name AS invigilator_name, i.dept 
                        FROM users u 
                        LEFT JOIN invigilators i ON u.invigilator_id = i.invigilator_id
                        ORDER BY u.user_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Examination System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
</head>
<body>
<?php include("includes/header.php"); ?>
<div class="container">
    <h2>👥 Manage Users</h2>
    <?= $message ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Linked Invigilator</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['user_id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= ucfirst($row['role']) ?></td>
                <td><?= $row['invigilator_name'] ?: "-" ?></td>
                <td><?= $row['dept'] ?: "-" ?></td>
                <td>
                    <!-- Delete -->
                    <a href="manage_users.php?delete=<?= $row['user_id'] ?>" 
                        class="btn btn-secondary" 
                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
