<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'captain') {
    header("Location: login.php");
    exit();
}

$logs = $conn->query("SELECT * FROM activity_logs ORDER BY date_time DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Activity Logs</title>
<style>
/* SAME CSS AS ABOVE (copy same CSS block here) */
</style>
</head>
<body>

<div class="wrapper">
<div class="sidebar">
<h3>Captain Panel</h3>
<a href="captain_dashboard.php">Dashboard</a>
<a href="captain_manage_admins.php">Manage Admins</a>
<a href="reports.php">Reports</a>
<a href="activity_logs.php">Activity Logs</a>
<a href="../logout.php">Logout</a>
</div>

<div class="main">
<div class="header">
Welcome, <?= htmlspecialchars($_SESSION['admin']) ?>
</div>

<div class="card">
<h2>System Activity Logs</h2>
<table>
<tr>
<th>User</th>
<th>Action</th>
<th>Date & Time</th>
</tr>

<?php while($row = $logs->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['username']) ?></td>
<td><?= htmlspecialchars($row['action']) ?></td>
<td><?= $row['date_time'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</div>
</body>
</html>