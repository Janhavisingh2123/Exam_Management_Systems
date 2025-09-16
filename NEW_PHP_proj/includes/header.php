<div class="navbar">
    <div class="navbar-brand"> Exam System</div>
    <div class="navbar-links">
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="manage_exams.php" class="nav-link">Exams</a>
            <a href="manage_invigilators.php" class="nav-link">Invigilators</a>
            <a href="assign_invigilators.php" class="nav-link">Assignments</a>
            <a href="reports.php" class="nav-link">Reports</a>
            <a href="register_user.php" class="nav-link">➕ Register User</a>
            <a href="manage_users.php" class="nav-link">👥 Manage Users</a>
        <?php else: ?>
            <a href="invigilator_dashboard.php" class="nav-link">My Duties</a>
        <?php endif; ?>
        <a href="logout.php" class="nav-link">Logout</a>
    </div>
</div>
