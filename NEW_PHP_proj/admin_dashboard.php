<?php
include("includes/auth.php");
requireAdmin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>
<div class="container">
    <h1>👨‍💼 Admin Dashboard</h1>
    <p>Welcome, Admin! Use the navigation to manage the system.</p>

    <div class="stats-grid">
        <div class="stat-card"><h3>✔️</h3><a href="manage_exams.php"><p>Manage Exams</p></a></div>
        <div class="stat-card"><h3>👨‍🏫</h3><p>Manage Invigilators</p></div>
        <div class="stat-card"><h3>📝</h3><p>Assignments</p></div>
        <div class="stat-card"><h3>📊</h3><p>Reports</p></div>
        
    </div>
    
</div>
</body>
</html>
