<?php
include("includes/auth.php");
requireAdmin();
include("includes/db_connect.php");

if (!isset($_GET['start']) || !isset($_GET['end'])) {
    die("❌ Invalid request.");
}

$start_date = $_GET['start'];
$end_date = $_GET['end'];

// Fetch assignments
$stmt = $conn->prepare("SELECT e.exam_date, e.exam_time, e.subject, e.dept AS exam_dept, e.hall,
                               i.name AS invigilator_name, i.dept AS invigilator_dept, a.status
                        FROM assignments a
                        JOIN exams e ON a.exam_id = e.exam_id
                        JOIN invigilators i ON a.invigilator_id = i.invigilator_id
                        WHERE e.exam_date BETWEEN ? AND ?
                        ORDER BY e.exam_date, e.exam_time");
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// Set headers for Excel export
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=invigilator_report_{$start_date}_to_{$end_date}.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Output table
echo "<table border='1'>";
echo "<tr>
        <th>Date</th>
        <th>Time</th>
        <th>Subject</th>
        <th>Exam Dept</th>
        <th>Hall</th>
        <th>Invigilator</th>
        <th>Invigilator Dept</th>
        <th>Status</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['exam_date']}</td>
            <td>{$row['exam_time']}</td>
            <td>{$row['subject']}</td>
            <td>{$row['exam_dept']}</td>
            <td>{$row['hall']}</td>
            <td>{$row['invigilator_name']}</td>
            <td>{$row['invigilator_dept']}</td>
            <td>{$row['status']}</td>
          </tr>";
}
echo "</table>";
exit;
?>
