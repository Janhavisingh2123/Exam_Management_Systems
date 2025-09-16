<?php
include("includes/auth.php");
requireAdmin(); // Only Admin can access
include("includes/db_connect.php");

$message = "";

// Handle Add Assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_invigilator'])) {
    $exam_id = intval($_POST['exam_id']);
    $invigilator_id = intval($_POST['invigilator_id']);

    // Fetch exam details
    $examQuery = $conn->prepare("SELECT dept, exam_date, exam_time FROM exams WHERE exam_id=?");
    $examQuery->bind_param("i", $exam_id);
    $examQuery->execute();
    $exam = $examQuery->get_result()->fetch_assoc();

    // Fetch invigilator details
    $invQuery = $conn->prepare("SELECT dept FROM invigilators WHERE invigilator_id=?");
    $invQuery->bind_param("i", $invigilator_id);
    $invQuery->execute();
    $invigilator = $invQuery->get_result()->fetch_assoc();

    // Rule 1: Prevent same department
    if (strcasecmp($exam['dept'], $invigilator['dept']) == 0) {
        $message = "<div class='alert alert-danger'>❌ Cannot assign invigilator from the same department as the exam ({$exam['dept']}).</div>";
    } else {
        // Rule 2: Prevent double booking
        $checkQuery = $conn->prepare("SELECT a.assignment_id 
                                      FROM assignments a 
                                      JOIN exams e ON a.exam_id = e.exam_id 
                                      WHERE a.invigilator_id=? 
                                      AND e.exam_date=? 
                                      AND e.exam_time=?");
        $checkQuery->bind_param("iss", $invigilator_id, $exam['exam_date'], $exam['exam_time']);
        $checkQuery->execute();
        $conflict = $checkQuery->get_result();

        if ($conflict->num_rows > 0) {
            $message = "<div class='alert alert-danger'>❌ This invigilator is already assigned at the same date and time.</div>";
        } else {
            // Insert assignment
            $stmt = $conn->prepare("INSERT INTO assignments (exam_id, invigilator_id, status) VALUES (?, ?, 'Confirmed')");
            $stmt->bind_param("ii", $exam_id, $invigilator_id);
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>✅ Invigilator assigned successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>❌ Error: " . $conn->error . "</div>";
            }
        }
    }
}

// Fetch exams & invigilators
$exams = $conn->query("SELECT * FROM exams ORDER BY exam_date, exam_time");
$invigilators = $conn->query("SELECT * FROM invigilators ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Invigilators - Examination System</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h2>👩‍🏫 Assign Invigilators</h2>
    <?= $message ?>

    <!-- Assignment Form -->
    <form method="POST" class="form">
        <div class="form-group">
            <label>Exam</label>
            <select name="exam_id" id="examSelect" class="form-control" required onchange="filterInvigilators(this)">
                <option value="">-- Select Exam --</option>
                <?php while ($exam = $exams->fetch_assoc()): ?>
                    <option value="<?= $exam['exam_id'] ?>" data-dept="<?= $exam['dept'] ?>">
                        <?= $exam['subject'] ?> (<?= $exam['dept'] ?>) - <?= $exam['exam_date'] ?> <?= $exam['exam_time'] ?> - <?= $exam['hall'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Invigilator</label>
            <select name="invigilator_id" id="invigilatorSelect" class="form-control" required>
                <option value="">-- Select Invigilator --</option>
                <?php while ($inv = $invigilators->fetch_assoc()): ?>
                    <option value="<?= $inv['invigilator_id'] ?>" data-dept="<?= $inv['dept'] ?>">
                        <?= $inv['name'] ?> (<?= $inv['dept'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" name="assign_invigilator" class="btn btn-primary">Assign Invigilator</button>
    </form>
</div>

<!-- jQuery + Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Apply Select2 (searchable dropdowns)
$(document).ready(function() {
    $('#examSelect').select2({
        placeholder: "Search for an exam...",
        allowClear: true
    });

    $('#invigilatorSelect').select2({
        placeholder: "Search for an invigilator...",
        allowClear: true
    });
});

// Filter invigilators when exam is selected (block same dept)
function filterInvigilators(examSelect) {
    let selectedOption = examSelect.options[examSelect.selectedIndex];
    let examDept = selectedOption.getAttribute("data-dept");

    let invOptions = document.querySelectorAll("#invigilatorSelect option");
    invOptions.forEach(opt => {
        if (!opt.value) return; // skip placeholder
        let invDept = opt.getAttribute("data-dept");
        opt.style.display = (invDept === examDept) ? "none" : "block";
    });

    // Refresh Select2 after filtering
    $('#invigilatorSelect').select2();
}
</script>

</body>
</html>
