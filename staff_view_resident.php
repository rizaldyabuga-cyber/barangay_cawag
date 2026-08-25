<?php
include "../db_connect.php";

$result = $conn->query("SELECT * FROM residents ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Residents</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
            padding: 50px;
            position: relative;
        }

        /* Back button */
        .back-btn {
            position: absolute;
            top: 30px;
            right: 50px;
            background: #333;
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            color: white;
            margin-right: 5px;
        }

        .btn-edit {
            background: #2196F3;
        }

        .btn-edit:hover {
            background: #1976D2;
        }

        .btn-delete {
            background: #f44336;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>

<!-- BACK TO DASHBOARD BUTTON -->
<a href="staff_dashboard.php" class="back-btn">Back to Dashboard</a>

<h2>Registered Residents</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Gender</th>
        <th>Birth Date</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['birth_date'] ?></td>
            <td><?= $row['contact_number'] ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
               
                <a class="btn btn-edit" href="staff_view_resident.php?id=<?= $row['id'] ?>">View</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
