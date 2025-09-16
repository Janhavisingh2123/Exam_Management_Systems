<?php
include("includes/auth.php");
requireAdmin(); 
include("includes/db_connect.php");

$message = "";

// ====================
// Add Exam
// ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_exam'])) {
    $subject = trim($_POST['subject']);
    $dept = trim($_POST['dept']);
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];
    $hall = trim($_POST['hall']);

    $stmt = $conn->prepare("INSERT INTO exams (subject, dept, exam_date, exam_time, hall) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $subject, $dept, $exam_date, $exam_time, $hall);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Exam added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
    }
}

// ====================
// Delete Exam
// ====================
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($conn->query("DELETE FROM exams WHERE exam_id=$id")) {
        $message = "<div class='alert alert-success'>✅ Exam deleted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error deleting exam: " . $conn->error . "</div>";
    }
}

// ====================
// Load Exam for Editing
// ====================
$edit_exam = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM exams WHERE exam_id=$id");
    if ($res->num_rows > 0) {
        $edit_exam = $res->fetch_assoc();
    }
}

// ====================
// Save Edited Exam
// ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_exam'])) {
    $exam_id = intval($_POST['exam_id']);
    $subject = trim($_POST['subject']);
    $dept = trim($_POST['dept']);
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];
    $hall = trim($_POST['hall']);

    $stmt = $conn->prepare("UPDATE exams SET subject=?, dept=?, exam_date=?, exam_time=?, hall=? WHERE exam_id=?");
    $stmt->bind_param("sssssi", $subject, $dept, $exam_date, $exam_time, $hall, $exam_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✅ Exam updated successfully!</div>";
        $edit_exam = null; // reset form
    } else {
        $message = "<div class='alert alert-danger'>❌ Error updating exam: " . $conn->error . "</div>";
    }
}

// ====================
// Fetch All Exams
// ====================
$result = $conn->query("SELECT * FROM exams ORDER BY exam_date, exam_time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Exams - Examination System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h2>📚 Manage Exams</h2>
    <?= $message ?>

    <!-- Add/Edit Exam Form -->
    <form method="POST" class="form">
        <h3><?= $edit_exam ? "✏️ Edit Exam (ID: {$edit_exam['exam_id']})" : "➕ Add New Exam" ?></h3>
        
        <?php if ($edit_exam): ?>
            <input type="hidden" name="exam_id" value="<?= $edit_exam['exam_id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" required 
                   value="<?= $edit_exam ? htmlspecialchars($edit_exam['subject']) : "" ?>">
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="dept" class="form-control" required 
                   value="<?= $edit_exam ? htmlspecialchars($edit_exam['dept']) : "" ?>">
        </div>
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="exam_date" class="form-control" required 
                   value="<?= $edit_exam ? $edit_exam['exam_date'] : "" ?>">
        </div>
        <div class="form-group">
            <label>Time</label>
            <input type="time" name="exam_time" class="form-control" required 
                   value="<?= $edit_exam ? $edit_exam['exam_time'] : "" ?>">
        </div>
        <div class="form-group">
            <label>Hall</label>
            <input type="text" name="hall" class="form-control" required 
                   value="<?= $edit_exam ? htmlspecialchars($edit_exam['hall']) : "" ?>">
        </div>
        
        <button type="submit" name="<?= $edit_exam ? 'update_exam' : 'add_exam' ?>" class="btn btn-primary">
            <?= $edit_exam ? "💾 Update Exam" : "➕ Add Exam" ?>
        </button>
        
        <?php if ($edit_exam): ?>
            <a href="manage_exams.php" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
    </form>

    <!-- Exam List -->
    <h3 style="margin-top:30px;">📋 Current Exams</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Hall</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['exam_id'] ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['dept']) ?></td>
                <td><?= $row['exam_date'] ?></td>
                <td><?= $row['exam_time'] ?></td>
                <td><?= htmlspecialchars($row['hall']) ?></td>
                <td>
                    <a href="manage_exams.php?edit=<?= $row['exam_id'] ?>" class="btn btn-primary">Edit</a>
                    <a href="manage_exams.php?delete=<?= $row['exam_id'] ?>" 
                       class="btn btn-secondary " 
                       onclick="return confirm('Are you sure you want to delete this exam?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
