<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin']) || $_SESSION['role'] != 'staff') {
    header("Location: login.php");
    exit();
}
$total_residents = $conn->query("SELECT COUNT(*) FROM residents")->fetch_row()[0];
$male = $conn->query("SELECT COUNT(*) FROM residents WHERE gender='Male'")->fetch_row()[0];
$female = $conn->query("SELECT COUNT(*) FROM residents WHERE gender='Female'")->fetch_row()[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barangay Cawag Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            margin: 0;
            min-height: 100vh;
            background: url('../image/cawag.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: -1;
        }

        /* HEADER */
        .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background: #2c3e50;
    color: white;
}

.header h2 {
    margin: 0;
}

.header-buttons {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 15px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

.btn:hover {
    background: #2980b9;
}

.logout {
    background: #e74c3c;
}

.logout:hover {
    background: #c0392b;
}

        /* CARDS */
        .container {
            padding: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .card h3 { font-weight: 400; }
        .card p { font-size: 32px; font-weight: 600; }

        .card a {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 20px;
            border-radius: 25px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            transition: 0.3s;
        }

        .card a:hover {
            background: #43a047;
        }
    </style>
</head>
<body>

    <div class="header">
    <h2>Welcome, Staff</h2>
  <div class="header-buttons">
       <a href="staff_barangay_record.php" class="btn">Incident Records</a>
        <a href="../logout.php" class="btn logout">Logout</a>
    </div>
   
</div>

    <div class="container">
        <div class="card">
            <h3>Total Residents</h3>
            <p><?= $total_residents ?></p>
            <a href="staff_resident.php">View Residents</a>
        </div>

        <div class="card">
            <h3>Male Residents</h3>
            <p><?= $male ?></p>
        </div>

        <div class="card">
            <h3>Female Residents</h3>
            <p><?= $female ?></p>
        </div>

       
    </div>

    <div class="container">
        <div class="card">
            <h3></h3>
            <p>BLANK</p>
            <a href="residents.php"></a>
        </div>

        <div class="card">
            <h3></h3>
            <p>BLANK</p>
        </div>

        <div class="card">
            <h3></h3>
            <p>BLANK</p>
        </div>

    </div>

</body>
</html>
