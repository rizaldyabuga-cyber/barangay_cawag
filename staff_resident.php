<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['staff'])) {
    header("Location: login.php");
    exit();
}

include "../db_connect.php";

/* ===========================
   DASHBOARD STATISTICS
=========================== */

$totalResidents = $conn->query("SELECT COUNT(*) FROM residents")->fetch_row()[0];

$maleResidents = $conn->query("
    SELECT COUNT(*)
    FROM residents
    WHERE gender='Male'
")->fetch_row()[0];

$femaleResidents = $conn->query("
    SELECT COUNT(*)
    FROM residents
    WHERE gender='Female'
")->fetch_row()[0];

$totalCertificates = $conn->query("
    SELECT COUNT(*)
    FROM certificates
")->fetch_row()[0];

$totalRecords = $conn->query("
    SELECT COUNT(*)
    FROM barangay_records
")->fetch_row()[0];

/* ===========================
   RECENT ANNOUNCEMENTS
=========================== */

$announcementResult = $conn->query("
    SELECT *
    FROM announcements
    ORDER BY created_at DESC
    LIMIT 3
");

/* ===========================
   RECENT CERTIFICATES
=========================== */

$recentCertificates = $conn->query("
    SELECT resident_name,
           certificate_type,
           date_issued
    FROM certificates
    ORDER BY id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Staff Dashboard | Barangay Cawag Management System</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{

    background:url("../image/cawag.jpg") center center/cover fixed;
    min-height:100vh;
    color:#fff;

}

body::before{

    content:"";
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    z-index:-1;

}

/* ======================
SIDEBAR
====================== */

.sidebar{

    position:fixed;
    left:0;
    top:0;
    width:250px;
    height:100%;
    background:#1f2d3d;
    box-shadow:4px 0 15px rgba(0,0,0,.35);
    overflow:auto;

}

.sidebar h2{

    text-align:center;
    padding:25px 10px;
    border-bottom:1px solid rgba(255,255,255,.1);
    font-size:22px;

}

.sidebar a{

    display:block;
    padding:15px 25px;
    color:white;
    text-decoration:none;
    transition:.3s;

}

.sidebar a:hover{

    background:#34495e;
    padding-left:35px;

}

.logout{

    background:#c0392b;
    margin-top:25px;

}

.logout:hover{

    background:#a93226;

}

/* ======================
MAIN
====================== */

.main-content{

    margin-left:250px;
    padding:35px;

}

.top-bar{

    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;

}

.welcome h2{

    font-size:30px;
    margin-bottom:8px;

}

.welcome p{

    opacity:.9;

}

.global-search{

    display:flex;

}

.global-search input{

    width:330px;
    padding:12px 18px;
    border:none;
    outline:none;
    border-radius:30px;
    font-size:15px;

}

/* ======================
CARDS
====================== */

.dashboard-cards{

    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:22px;

}

.card{

    background:rgba(255,255,255,.15);
    backdrop-filter:blur(12px);
    border-radius:18px;
    padding:25px;
    transition:.3s;
    box-shadow:0 10px 25px rgba(0,0,0,.25);

}

.card:hover{

    transform:translateY(-6px);

}

.card h3{

    font-size:17px;
    margin-bottom:10px;
    font-weight:500;

}

.card p{

    font-size:36px;
    font-weight:bold;

}

.card a{

    display:inline-block;
    margin-top:18px;
    text-decoration:none;
    color:white;
    background:#27ae60;
    padding:10px 18px;
    border-radius:30px;
    transition:.3s;

}

.card a:hover{

    background:#1e8449;

}

.blue{

    border-top:5px solid #3498db;

}

.green{

    border-top:5px solid #27ae60;

}

.orange{

    border-top:5px solid #e67e22;

}

.purple{

    border-top:5px solid #8e44ad;

}

.red{

    border-top:5px solid #e74c3c;

}

.teal{

    border-top:5px solid #16a085;

}

/* ======================
QUICK ACTIONS
====================== */

.quick-actions{

    margin-top:35px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;

}

.quick-card{

    text-align:center;
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(10px);
    border-radius:18px;
    padding:30px;
    transition:.3s;

}

.quick-card:hover{

    transform:translateY(-6px);

}

.quick-card h2{

    font-size:45px;
    margin-bottom:15px;

}

.quick-card a{

    display:inline-block;
    margin-top:12px;
    text-decoration:none;
    color:white;
    background:#2980b9;
    padding:10px 18px;
    border-radius:25px;

}

/* ======================
PANELS
====================== */

.info-section{

    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
    margin-top:40px;

}

.panel{

    background:rgba(255,255,255,.12);
    backdrop-filter:blur(10px);
    border-radius:18px;
    padding:25px;

}

.panel h3{

    margin-bottom:20px;

}

.item{

    padding:12px 0;
    border-bottom:1px solid rgba(255,255,255,.15);

}

.item:last-child{

    border:none;

}

.item small{

    color:#ddd;

}

/* ======================
CHARTS
====================== */

.charts{

    margin-top:35px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(450px,1fr));
    gap:25px;

}

.chart-box{

    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,.2);

}

.chart-box h3{

    text-align:center;
    margin-bottom:20px;
    color:#2c3e50;

}

/* ======================
RESPONSIVE
====================== */

@media(max-width:900px){

.sidebar{

    position:relative;
    width:100%;
    height:auto;

}

.main-content{

    margin-left:0;

}

.info-section{

    grid-template-columns:1fr;

}

.charts{

    grid-template-columns:1fr;

}

.global-search input{

    width:100%;

}

}

</style>

</head>

<body>
    <!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>Barangay Cawag</h2>

    <a href="staff_dashboard.php">🏠 Dashboard</a>
    <a href="staff_resident.php">👥 Residents</a>
    <a href="../register.php">➕ Register Resident</a>
    <a href="../certificates.php">📄 Certificates</a>
    <a href="staff_barangay_record.php">🚨 Incident Records</a>
    <a href="../announcements.php">📢 Announcements</a>
    <a href="profile.php">👤 My Profile</a>

    <a href="../logout.php" class="logout">
        🚪 Logout
    </a>

</div>

<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">

    <div class="top-bar">

        <div class="welcome">

            <h2>
                Welcome,
                <?= htmlspecialchars($_SESSION['staff']); ?>
            </h2>

            <p>
                <?= date("l, F d, Y"); ?>
            </p>

        </div>

        <form class="global-search"
              action="search.php"
              method="GET">

            <input
                type="text"
                name="q"
                placeholder="🔍 Search residents, certificates or records..."
                required>

        </form>

    </div>

    <!-- =====================
         DASHBOARD CARDS
    ====================== -->

    <div class="dashboard-cards">

        <div class="card blue">

            <h3>Total Residents</h3>

            <p><?= $totalResidents ?></p>

            <a href="residents.php">
                View Residents
            </a>

        </div>

        <div class="card green">

            <h3>Male Residents</h3>

            <p><?= $maleResidents ?></p>

        </div>

        <div class="card purple">

            <h3>Female Residents</h3>

            <p><?= $femaleResidents ?></p>

        </div>

        <div class="card orange">

            <h3>Certificates Issued</h3>

            <p><?= $totalCertificates ?></p>

            <a href="certificates.php">
                View Certificates
            </a>

        </div>

        <div class="card red">

            <h3>Incident Records</h3>

            <p><?= $totalRecords ?></p>

            <a href="staff_barangay_record.php">
                View Records
            </a>

        </div>

    </div>

    <!-- =====================
         QUICK ACTIONS
    ====================== -->

    <h2 style="margin-top:45px;margin-bottom:20px;">
        Quick Actions
    </h2>

    <div class="quick-actions">

        <div class="quick-card">

            <h2>👥</h2>

            <h3>Register Resident</h3>

            <a href="register.php">
                Open
            </a>

        </div>

        <div class="quick-card">

            <h2>📄</h2>

            <h3>Issue Certificate</h3>

            <a href="add_certificate.php">
                Open
            </a>

        </div>

        <div class="quick-card">

            <h2>🚨</h2>

            <h3>Add Incident</h3>

            <a href="add_record.php">
                Open
            </a>

        </div>

        <div class="quick-card">

            <h2>🔍</h2>

            <h3>Search Resident</h3>

            <a href="residents.php">
                Search
            </a>

        </div>

    </div>

    <!-- =====================
         INFORMATION PANELS
    ====================== -->

    <div class="info-section">

        <!-- ANNOUNCEMENTS -->

        <div class="panel">

            <h3>
                📢 Latest Announcements
            </h3>

            <?php

            if($announcementResult && $announcementResult->num_rows > 0){

                while($row = $announcementResult->fetch_assoc()){

            ?>

            <div class="item">

                <strong>
                    <?= htmlspecialchars($row['title']) ?>
                </strong>

                <br>

                <small>
                    <?= date("F d, Y", strtotime($row['created_at'])) ?>
                </small>

                <p style="margin-top:8px;">

                    <?= nl2br(htmlspecialchars(substr($row['message'],0,120))) ?>

                    ...

                </p>

            </div>

            <?php

                }

            }else{

                echo "<p>No announcements available.</p>";

            }

            ?>

            <br>

            <a href="announcements.php"
               style="color:white;text-decoration:none;font-weight:bold;">

                View All →

            </a>

        </div>

        <!-- RECENT CERTIFICATES -->

        <div class="panel">

            <h3>
                📄 Recent Certificates
            </h3>

            <?php

            if($recentCertificates && $recentCertificates->num_rows > 0){

                while($cert = $recentCertificates->fetch_assoc()){

            ?>

            <div class="item">

                <strong>

                    <?= htmlspecialchars($cert['resident_name']) ?>

                </strong>

                <br>

                <small>

                    <?= htmlspecialchars($cert['certificate_type']) ?>

                </small>

                <br>

                <small>

                    <?= date("F d, Y", strtotime($cert['date_issued'])) ?>

                </small>

            </div>

            <?php

                }

            }else{

                echo "<p>No certificates found.</p>";

            }

            ?>

        </div>

    </div>

   
</div>

</body>
</html>