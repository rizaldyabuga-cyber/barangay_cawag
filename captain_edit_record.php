<?php
session_start();
include "../db_connect.php";

// Check if logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Check if role is captain
if ($_SESSION['role'] !== 'captain') {
    die("Access Denied. Captain only.");
}

// Regenerate session ID for security
session_regenerate_id(true);

// Example statistics queries
$totalResidents = $conn->query("SELECT COUNT(*) as total FROM residents")->fetch_assoc()['total'];
$totalRecords = $conn->query("SELECT COUNT(*) as total FROM barangay_records")->fetch_assoc()['total'];
$totalBlotter = $conn->query("SELECT COUNT(*) as total FROM barangay_records WHERE record_type='Blotter'")->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Captain Dashboard</title>
    <style>
        body {
            margin:0;
            font-family: Arial, sans-serif;
            background:#f4f6f9;
        }
        .header {
            background:#2c3e50;
            color:white;
            padding:15px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .header h2 {
            margin:0;
        }
        .container {
            padding:30px;
        }
        .cards {
            display:flex;
            gap:20px;
            flex-wrap:wrap;
        }
        .card {
            background:white;
            padding:20px;
            flex:1;
            min-width:250px;
            border-radius:10px;
            box-shadow:0 4px 8px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin:0;
            font-size:18px;
            color:#555;
        }
        .card p {
            font-size:28px;
            margin:10px 0 0;
            font-weight:bold;
        }
        .logout {
            background:#e74c3c;
            color:white;
            padding:8px 15px;
            text-decoration:none;
            border-radius:5px;
        }
        .menu {
            margin-top:30px;
        }
        .menu a {
            display:inline-block;
            margin-right:15px;
            padding:10px 15px;
            background:#3498db;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Barangay Captain Dashboard</h2>
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?> |
        <a class="logout" href="../logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="cards">
        <div class="card">
            <h3>Total Residents</h3>
            <p><?php echo $totalResidents; ?></p>
        </div>

        <div class="card">
            <h3>Total Records</h3>
            <p><?php echo $totalRecords; ?></p>
        </div>

        <div class="card">
            <h3>Total Blotter Cases</h3>
            <p><?php echo $totalBlotter; ?></p>
        </div>
    </div>

    <div class="menu">
        <a href="captain_resident.php">View Residents</a>
        <a href="captain_barangay_record.php">View Records</a>
        <a href="reports.php">View Reports</a>
        <a href="captain_manage_admins.php">Manage Admins</a>
    </div>

</div>

</body>
</html>