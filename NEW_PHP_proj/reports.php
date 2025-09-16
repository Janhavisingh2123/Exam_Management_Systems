<?php
include("includes/auth.php");
requireAdmin(); // Only Admin can access
include("includes/db_connect.php");

$message = "";
$assignments = [];

// Handle Report Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_report'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!$start_date || !$end_date) {
        $message = "<div class='alert alert-danger'>❌ Please select both start and end dates.</div>";
    } else {
        $stmt = $conn->prepare("SELECT e.subject, e.dept AS exam_dept, e.exam_date, e.exam_time, e.hall,
                                       i.name AS invigilator_name, i.dept AS invigilator_dept, 
                                       i.employee_id, i.email, i.phone,
                                       a.status
                                FROM assignments a
                                JOIN exams e ON a.exam_id = e.exam_id
                                JOIN invigilators i ON a.invigilator_id = i.invigilator_id
                                WHERE e.exam_date BETWEEN ? AND ?
                                ORDER BY e.exam_date, e.exam_time");
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $assignments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($assignments)) {
            $message = "<div class='alert alert-warning'>⚠️ No assignments found for the selected period.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invigilator Reports - Examination System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h2>📊 Invigilator Assignment Reports</h2>
    <?= $message ?>

    <!-- Report Form -->
    <form method="POST" class="form" style="margin-bottom:20px;">
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" value="<?= $_POST['start_date'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" value="<?= $_POST['end_date'] ?? '' ?>" required>
        </div>
        <button type="submit" name="generate_report" class="btn btn-primary">Generate Report</button>
    </form>

    <?php if (!empty($assignments)): ?>
        <h3>📅 Report from <?= htmlspecialchars($start_date) ?> to <?= htmlspecialchars($end_date) ?></h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Exam Dept</th>
                    <th>Hall</th>
                    <th>Invigilator</th>
                    <th>Employee ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Invigilator Dept</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $row): ?>
                    <tr>
                        <td><?= $row['exam_date'] ?></td>
                        <td><?= $row['exam_time'] ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['exam_dept']) ?></td>
                        <td><?= htmlspecialchars($row['hall']) ?></td>
                        <td><?= htmlspecialchars($row['invigilator_name']) ?></td>
                        <td><?= htmlspecialchars($row['employee_id']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['invigilator_dept']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Export Options -->
        <div style="margin-top:15px;">
            <button onclick="window.print()" class="btn btn-secondary">🖨️ Print Report</button>
            <a href="export_pdf.php?start=<?= $start_date ?>&end=<?= $end_date ?>" class="btn btn-success">📄 Export to PDF</a>
            <a href="export_excel.php?start=<?= $start_date ?>&end=<?= $end_date ?>" class="btn btn-primary">📊 Export to Excel</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
