<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$certificates = $conn->query("SELECT * FROM certificates ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Certificates</title>

    <style>
        body{
            font-family:Arial,sans-serif;
            margin:0;
            background:#f4f6f9;
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:15px 30px;
            background:#2c3e50;
            color:#fff;
        }

        .header-buttons{
            display:flex;
            gap:10px;
        }

        .btn{
            padding:8px 15px;
            background:#3498db;
            color:#fff;
            text-decoration:none;
            border-radius:6px;
        }

        .logout{
            background:#e74c3c;
        }

        .container{
            padding:30px;
        }

        .add-btn{
            display:inline-block;
            margin-bottom:15px;
            padding:10px 18px;
            background:#27ae60;
            color:#fff;
            text-decoration:none;
            border-radius:6px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:#fff;
        }

        th,td{
            border:1px solid #ddd;
            padding:10px;
            text-align:center;
        }

        th{
            background:#3498db;
            color:white;
        }

        tr:nth-child(even){
            background:#f2f2f2;
        }

        .edit-btn{
            background:#f39c12;
            color:#fff;
            padding:5px 10px;
            text-decoration:none;
            border-radius:5px;
        }

        .print-btn{
            background:#27ae60;
            color:#fff;
            padding:5px 10px;
            text-decoration:none;
            border-radius:5px;
        }

        .delete-btn{
            background:#e74c3c;
            color:#fff;
            padding:5px 10px;
            text-decoration:none;
            border-radius:5px;
        }
    </style>

</head>
<body>

<div class="header">
    <h2>Certificates - Welcome, <?= $_SESSION['admin']; ?></h2>

    <div class="header-buttons">
        <a href="dashboard.php" class="btn">Dashboard</a>
        <a href="logout.php" class="btn logout">Logout</a>
    </div>
</div>

<div class="container">

    <a href="add_certificate.php" class="add-btn">+ Add Certificate</a>

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
            <th>Actions</th>
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

            <td>

                <a href="edit_certificate.php?id=<?= $row['id']; ?>" class="edit-btn">
                    Edit
                </a>

                <a href="print_certificate.php?id=<?= $row['id']; ?>" class="print-btn" target="_blank">
                    Print
                </a>

                <a href="delete_certificate.php?id=<?= $row['id']; ?>"
                   class="delete-btn"
                   onclick="return confirm('Delete this certificate?');">
                    Delete
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>