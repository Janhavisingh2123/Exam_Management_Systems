<?php
include("includes/auth.php");
requireAdmin(); // ✅ Only Admin can access
include("includes/db_connect.php");

$message = "";

// ====================
// Add Invigilator
// ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_invigilator'])) {
    $name = trim($_POST['name']);
    $dept = trim($_POST['dept']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("INSERT INTO invigilators (name, dept, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $dept, $email, $phone);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Invigilator added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
}

// ====================
// Delete Invigilator
// ====================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM invigilators WHERE invigilator_id=$id")) {
        $message = "<div class='alert alert-success'>✅ Invigilator deleted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error deleting invigilator: " . $conn->error . "</div>";
    }
}

// ====================
// Load Invigilator for Editing
// ====================
$edit_invigilator = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM invigilators WHERE invigilator_id=$id");
    if ($res->num_rows > 0) {
        $edit_invigilator = $res->fetch_assoc();
    }
}

// ====================
// Save Edited Invigilator
// ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_invigilator'])) {
    $invigilator_id = intval($_POST['invigilator_id']);
    $name = trim($_POST['name']);
    $dept = trim($_POST['dept']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("UPDATE invigilators SET name=?, dept=?, email=?, phone=? WHERE invigilator_id=?");
    $stmt->bind_param("ssssi", $name, $dept, $email, $phone, $invigilator_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Invigilator updated successfully!</div>";
        $edit_invigilator = null; // reset form
    } else {
        $message = "<div class='alert alert-danger'>❌ Error updating invigilator: " . $conn->error . "</div>";
    }
}

// ====================
// Fetch All Invigilators
// ====================
$result = $conn->query("SELECT * FROM invigilators ORDER BY dept, name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Invigilators - Examination System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h2>👨‍🏫 Manage Invigilators</h2>
    <?= $message ?>

    <!-- Add/Edit Invigilator Form -->
    <form method="POST" class="form">
        <h3><?= $edit_invigilator ? "✏️ Edit Invigilator (ID: {$edit_invigilator['invigilator_id']})" : "➕ Add New Invigilator" ?></h3>
        
        <?php if ($edit_invigilator): ?>
            <input type="hidden" name="invigilator_id" value="<?= $edit_invigilator['invigilator_id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required 
                   value="<?= $edit_invigilator ? htmlspecialchars($edit_invigilator['name']) : "" ?>">
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="dept" class="form-control" required 
                   value="<?= $edit_invigilator ? htmlspecialchars($edit_invigilator['dept']) : "" ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= $edit_invigilator ? htmlspecialchars($edit_invigilator['email']) : "" ?>">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="<?= $edit_invigilator ? htmlspecialchars($edit_invigilator['phone']) : "" ?>">
        </div>
        
        <button type="submit" name="<?= $edit_invigilator ? 'update_invigilator' : 'add_invigilator' ?>" class="btn btn-primary">
            <?= $edit_invigilator ? "💾 Update Invigilator" : "➕ Add Invigilator" ?>
        </button>
        
        <?php if ($edit_invigilator): ?>
            <a href="manage_invigilators.php" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
    </form>

    <!-- Invigilator List -->
    <h3 style="margin-top:30px;">📋 Current Invigilators</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['invigilator_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['dept']) ?></td>
                <td><?= $row['email'] ?: "-" ?></td>
                <td><?= $row['phone'] ?: "-" ?></td>
                <td>
                    <a href="manage_invigilators.php?edit=<?= $row['invigilator_id'] ?>" class="btn btn-primary">Edit</a>
                    <a href="manage_invigilators.php?delete=<?= $row['invigilator_id'] ?>" 
                       class="btn btn-secondary" 
                       onclick="return confirm('Are you sure you want to delete this invigilator?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
