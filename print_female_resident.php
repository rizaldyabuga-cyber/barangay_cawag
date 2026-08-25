<?php
session_start();
include "../db_connect.php";
include "../includes/system_info.php";
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

// Get all certificates
$certificates = $conn->query("
SELECT *
FROM certificates
ORDER BY date_issued DESC
");

// Get statistics
$total = $conn->query("
SELECT COUNT(*)
FROM certificates
")->fetch_row()[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Certificate Report List</title>

    <style>

        body{
            font-family:Arial, sans-serif;
            margin:40px;
        }

        .header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.title{
    text-align:center;
    flex:1;
}

.title p{
    margin:2px;
}

.title h2{
    margin:5px;
}

.title h3{
    margin-top:10px;
}

.logo-left,
.logo-right{
    width:90px;
    height:90px;
}

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table, th, td{
            border:1px solid black;
        }

        th, td{
            padding:8px;
            text-align:center;
            font-size:14px;
        }

        th{
            background:#ddd;
        }

        .summary{
            margin-top:25px;
            font-size:16px;
            font-weight:bold;
        }

        @media print{

            .no-print{
                display:none;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <img src="../image/barangay_logo.jpg" class="logo-left">

    <div class="title">

        <p>Republic of the Philippines</p>
        <p>Province of Zambales</p>
        <p>Municipality of Subic</p>

        <h2>BARANGAY CAWAG</h2>

        <h3>CERTIFICATE REPORT LIST</h3>
<p style="text-align:right;">
    Date Printed:
    <?= date("F d, Y h:i A"); ?>
</p>
    </div>

    <img src="../image/subic_logo.jpg" class="logo-right">

</div>

<table>

<tr>
    <th>ID</th>
    <th>Resident Name</th>
    <th>Certificate Type</th>
    <th>Purpose</th>
    <th>Issued By</th>
    <th>Control No.</th>
    <th>Date Issued</th>
    <th>Resident ID</th>
</tr>

<?php while($row = $certificates->fetch_assoc()) { ?>

<tr>
    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['resident_name']); ?></td>
    <td><?= htmlspecialchars($row['certificate_type']); ?></td>
    <td><?= htmlspecialchars($row['purpose']); ?></td>
    <td><?= htmlspecialchars($row['issued_by']); ?></td>
    <td><?= htmlspecialchars($row['control_no']); ?></td>
    <td><?= htmlspecialchars($row['date_issued']); ?></td>
    <td><?= htmlspecialchars($row['resident_id']); ?></td>
</tr>

<?php } ?>
</table>

<div class="summary">
    <p>Total Certificates Issued: <?= $total; ?></p>
</div>

<br><br>

<button class="no-print" onclick="window.print()">
    Print Report
</button>

<button class="no-print" onclick="history.back()">
    Back
</button>

</body>
</html>