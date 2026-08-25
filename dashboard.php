<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db_connect.php";

// Resident Statistics
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

// Certificates
$totalCertificates = $conn->query("
SELECT COUNT(*)
FROM certificates
")->fetch_row()[0];

// Barangay Records
$totalRecords = $conn->query("
SELECT COUNT(*)
FROM barangay_records
")->fetch_row()[0];

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
            background: url('image/cawag.jpg') no-repeat center center fixed;
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
      .sidebar{
    position: fixed;
    top: 0;
    left: 0;
    width: 240px;
    height: 100%;
    background: #2c3e50;
    padding-top: 20px;
    box-shadow: 3px 0 10px rgba(0,0,0,.3);
}

.sidebar h2{
    color:#fff;
    text-align:center;
    margin-bottom:30px;
    font-size:22px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:15px 25px;
    transition:.3s;
}

.sidebar a:hover{
    background:#34495e;
}

.sidebar .logout{
    background:#e74c3c;
    margin-top:20px;
}

.sidebar .logout:hover{
    background:#c0392b;
}

.main-content{
    margin-left:260px;
    padding:30px;
    color:white;
    position:relative;   /* Important */
}

.global-search{
    position:absolute;
    top:30px;
    right:30px;
    display:flex;
    gap:10px;
    align-items:center;
}

.global-search input{
    width:350px;
    padding:10px 15px;
    border:none;
    border-radius:25px;
    outline:none;
}

.global-search button{
    padding:10px 20px;
    border:none;
    border-radius:25px;
    background:#27ae60;
    color:white;
    cursor:pointer;
}

.global-search button:hover{
    background:#219150;
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
        
        .charts{

    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(450px,1fr));
    gap:25px;
    margin-top:35px;

}

.chart-box{

    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,.1);

}

.chart-box h3{

    margin-bottom:20px;
    color:#2c3e50;
    text-align:center;

}
        
        .announcement-card{
    grid-column: span 2;
}
        
        .announcement-card{

    text-align:left;
    min-height:260px;

}

.announcement-card h3{

    margin-bottom:20px;

}

.announcement-item{

    padding:12px 0;
    border-bottom:1px solid rgba(255,255,255,.2);

}

.announcement-item:last-child{

    border-bottom:none;

}

.announcement-item strong{

    font-size:16px;

}

.announcement-item small{

    color:#ddd;

}

.announcement-item p{

    margin-top:6px;
    font-size:14px;
    line-height:1.5;

}

.announcement-card a{

    display:inline-block;
    margin-top:15px;
    color:#fff;
    font-weight:bold;
    text-decoration:none;

}
     
    </style>
</head>
<body>

   <div class="sidebar">
    <h2>Barangay Cawag</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="residents.php">👥 Residents</a>
    <a href="barangay_record.php">🚨 Incident Records</a>
    <a href="certificates.php">📄 Certificates</a>
        <a href="reports/index.php">📊 Reports</a>
        <a href="utilities/index.php">⚙️ Utilities</a>
    <a href="register.php">➕ Register Resident</a>
       <a href="add_user.php"> 👥 Add User</a>
    <a href="logout.php" class="logout">🚪 Logout</a>
</div>

<div class="main-content">

    <h2>Welcome, <?= $_SESSION['admin']; ?></h2>

   <form action="search.php" method="GET" class="global-search">

    <input
        type="text"
        name="q"
        placeholder="🔍 Search residents, certificates, records..."
        required>

</form>
       
    <div class="container">
        <div class="card">
            <h3>Total Residents</h3>
            <p><?= $total_residents ?></p>
            <a href="residents.php">View Residents</a>
        </div>

        <div class="card">
            <h3>Male Residents</h3>
            <p><?= $male ?></p>
        </div>

        <div class="card">
            <h3>Female Residents</h3>
            <p><?= $female ?></p>
        </div>

        <div class="card">
            <h3>Add Resident</h3>
            <p>+</p>
            <a href="register.php">Register New</a>
        </div>
        
         <div class="card">
            <h3>Total Certificates Issued</h3>
             <p><?= $totalCertificates ?></p>
        </div>
        
         <div class="card">
            <h3>Total Incidents Record</h3>
           <p><?= $totalRecords ?></p>
        </div>
        
    </div>
    
    
    
    <div class="announcement-card card">

    <h3>📢 Latest Announcements</h3>

    <?php
    $result = $conn->query("
        SELECT *
        FROM announcements
        ORDER BY created_at DESC
        LIMIT 3
    ");

    if($result->num_rows > 0){

        while($row = $result->fetch_assoc()){
    ?>

        <div class="announcement-item">

            <strong><?= htmlspecialchars($row['title']) ?></strong><br>

            <small>
                <?= date("F d, Y", strtotime($row['created_at'])) ?>
            </small>

            <p>
                <?= nl2br(htmlspecialchars(substr($row['message'],0,100))) ?>...
            </p>

        </div>

    <?php
        }

    }else{
    ?>

        <p>No announcements available.</p>

    <?php } ?>

    <a href="announcements.php">
        View All →
    </a>

</div>
        
        <div class="charts">

    <div class="chart-box">
        <h3>Residents by Gender</h3>

        <canvas id="genderChart"></canvas>
    </div>

    <div class="chart-box">
        <h3>System Overview</h3>

        <canvas id="overviewChart"></canvas>
    </div>

</div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

const genderChart = new Chart(document.getElementById('genderChart'),{

    type:'pie',

    data:{

        labels:['Male','Female'],

        datasets:[{

            data:[
                <?= $maleResidents ?>,
                <?= $femaleResidents ?>
            ]

        }]

    }

});

const overviewChart = new Chart(document.getElementById('overviewChart'),{

    type:'bar',

    data:{

        labels:[
            'Residents',
            'Certificates',
            'Records'
        ],

        datasets:[{

            label:'Total',

            data:[
                <?= $totalResidents ?>,
                <?= $totalCertificates ?>,
                <?= $totalRecords ?>
            ]

        }]

    },

    options:{
        responsive:true
    }

});

</script>
</body>
</html>
