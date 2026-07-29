<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch all records with resident names
$records = $conn->query("
    SELECT br.*, r.full_name 
    FROM barangay_records br 
    LEFT JOIN residents r ON br.resident_id = r.id 
    ORDER BY br.date_recorded DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barangay Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6f9;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: rgba(44, 62, 80, 0.9);
            color: white;
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
        }

        .logout {
            background: #e74c3c;
        }

        /* Container */
        .container {
            padding: 30px;
        }

        .add-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .add-btn:hover { background: #219150; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #3498db;
            color: white;
        }

        tr:nth-child(even) { background: #f2f2f2; }

        /* Action buttons */
        .edit-btn {
            background: #f39c12;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }

        .delete-btn {
            background: #e74c3c;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
        }

        .edit-btn:hover { background: #d68910; }
        .delete-btn:hover { background: #c0392b; }

        /* Responsive */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; }
            th, td { padding: 10px; text-align: right; position: relative; }
            td::before { content: attr(data-label); position: absolute; left: 10px; width: 45%; font-weight: bold; text-align: left; }
            .header { flex-direction: column; align-items: flex-start; }
            .header-buttons { margin-top: 10px; }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Barangay Records - Welcome, <?= $_SESSION['admin'] ?></h2>

    <div class="header-buttons">
        <a href="staff_dashboard.php" class="btn">Dashboard</a>
        <a href="../logout.php" class="btn logout">Logout</a>
    </div>
</div>

<div class="container">

    <!-- ADD BUTTON -->
   
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Record Type</th>
                <th>Description</th>
                <th>Date</th>
                <th>Resident</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($records->num_rows > 0) {
            while ($row = $records->fetch_assoc()) { ?>
                <tr>
                    <td data-label="ID"><?= $row['id'] ?></td>
                    <td data-label="Record Type"><?= htmlspecialchars($row['record_type']) ?></td>
                    <td data-label="Description"><?= htmlspecialchars($row['description']) ?></td>
                    <td data-label="Date"><?= htmlspecialchars($row['date_recorded']) ?></td>
                    <td data-label="Resident"><?= htmlspecialchars($row['full_name'] ?? 'Unknown') ?></td>
                    <td data-label="Status"><?= htmlspecialchars($row['status']) ?></td>
                    <td data-label="Actions">
                       
                    </td>
                </tr>
        <?php } } else { ?>
            <tr>
                <td colspan="7" style="text-align:center;">No records found.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>
