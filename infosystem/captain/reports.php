<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'captain') {
    header("Location: login.php");
    exit();
}

$residents = $conn->query("SELECT COUNT(*) as total FROM residents")->fetch_assoc()['total'];
$blotter = $conn->query("SELECT COUNT(*) as total FROM barangay_records WHERE record_type='Blotter'")->fetch_assoc()['total'];
$accident = $conn->query("SELECT COUNT(*) as total FROM barangay_records WHERE record_type='Accident'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.wrapper {
    display: flex;
}

.sidebar {
    width: 220px;
    background: #2c3e50;
    min-height: 100vh;
    color: white;
    padding-top: 20px;
}

.sidebar h3 {
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: white;
    padding: 12px 20px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #34495e;
}

.main {
    flex: 1;
    padding: 30px;
}

.header {
    background: white;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card h2 {
    margin-top: 0;
}

.card p {
    font-size: 16px;
    margin: 10px 0;
}

button {
    padding: 10px 15px;
    border: none;
    background: #3498db;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
}

button:hover {
    background: #2980b9;
}
</style>

</head>
<body>

<div class="wrapper">

    <div class="sidebar">
        <h3>Captain Panel</h3>
        <a href="captain_dashboard.php">Dashboard</a>
        <a href="manage_admins.php">Manage Admins</a>
        <a href="reports.php">Reports</a>
        <a href="captain_activity_log.php">Activity Logs</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">

        <div class="header">
            Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?>
        </div>

        <div class="card">
            <h2>Barangay Summary Report</h2>

            <p><strong>Total Residents:</strong> <?php echo $residents; ?></p>
            <p><strong>Total Blotter Cases:</strong> <?php echo $blotter; ?></p>
            <p><strong>Total Accidents:</strong> <?php echo $accident; ?></p>

            <button onclick="window.print()">Print Report</button>
        </div>

    </div>

</div>

</body>
</html>