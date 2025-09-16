<?php
include("includes/auth.php");
requireInvigilator();
include("includes/db_connect.php");

$invigilator_id = $_SESSION['invigilator_id'];
$stmt = $conn->prepare("SELECT e.exam_date, e.exam_time, e.subject, e.hall 
                        FROM assignments a
                        JOIN exams e ON a.exam_id = e.exam_id
                        WHERE a.invigilator_id=?");
$stmt->bind_param("i", $invigilator_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invigilator Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>
<div class="container">
    <h2>👨‍🏫 My Assigned Duties</h2>
    <table class="table">
        <thead>
            <tr><th>Date</th><th>Time</th><th>Subject</th><th>Hall</th></tr>
        </thead>
        <tbody>
            <?php while($row=$res->fetch_assoc()): ?>
            <tr>
                <td><?= $row['exam_date'] ?></td>
                <td><?= $row['exam_time'] ?></td>
                <td><?= $row['subject'] ?></td>
                <td><?= $row['hall'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
