<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

// Get all male residents
$residents = $conn->query("
SELECT *
FROM residents
WHERE gender='Male'
ORDER BY full_name ASC
");

// Get total male residents
$total = $conn->query("
SELECT COUNT(*)
FROM residents
WHERE gender='Male'
")->fetch_row()[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Male Resident Reports</title>

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

        <h3>MALE MASTER LIST</h3>
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
    <th>Full Name</th>
    <th>Gender</th>
    <th>Birth Date</th>
    <th>Contact</th>
    <th>Address</th>
    <th>Email</th>

</tr>

<?php while($row = $residents->fetch_assoc()) { ?>

<tr>

    <td><?= $row['id']; ?></td>
    <td><?= htmlspecialchars($row['full_name']); ?></td>
    <td><?= htmlspecialchars($row['gender']); ?></td>
    <td><?= htmlspecialchars($row['birth_date']); ?></td>
    <td><?= htmlspecialchars($row['contact_number']); ?></td>
    <td><?= htmlspecialchars($row['address']); ?></td>
    <td><?= htmlspecialchars($row['email']); ?></td>

</tr>

<?php } ?>

</table>

<div class="summary">

<p>Total Residents: <?= $total; ?></p>
<p>Male Residents: <?= $male; ?></p>
<p>Female Residents: <?= $female; ?></p>

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