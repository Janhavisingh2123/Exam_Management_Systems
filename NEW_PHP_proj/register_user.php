<?php
session_start();
include("includes/db_connect.php");

// Only admin can access registration page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $invigilator_id = !empty($_POST['invigilator_id']) ? $_POST['invigilator_id'] : NULL;

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, invigilator_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $hashedPassword, $role, $invigilator_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ User registered successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User - Examination System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h2>👤 Register New User</h2>
    <?= $message ?>

    <form method="POST" class="form">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" id="roleSelect" class="form-control" onchange="toggleInvigilatorSelect()" required>
                <option value="">-- Select Role --</option>
                <option value="admin">Admin</option>
                <option value="invigilator">Invigilator</option>
            </select>
        </div>

        <div class="form-group" id="invigilatorSelectBox" style="display:none;">
            <label>Link to Invigilator</label>
            <select name="invigilator_id" class="form-control">
                <option value="">-- Select Invigilator --</option>
                <?php
                $result = $conn->query("SELECT invigilator_id, name FROM invigilators");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['invigilator_id']}'>{$row['name']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- ✅ Submit Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Register User</button>
        </div>
    </form>
</div>

<script>
function toggleInvigilatorSelect() {
    let role = document.getElementById("roleSelect").value;
    let box = document.getElementById("invigilatorSelectBox");
    box.style.display = (role === "invigilator") ? "block" : "none";
}
</script>
</body>
</html>
