<?php
$conn = new mysqli("localhost", "root", "", "barangay_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    // Get resident information
    $stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Resident not found.";
        exit();
    }

    // Get barangay records of this resident
    $record_stmt = $conn->prepare("SELECT * FROM barangay_records WHERE resident_id = ? ORDER BY id DESC");
    $record_stmt->bind_param("i", $id);
    $record_stmt->execute();
    $record_result = $record_stmt->get_result();

} else {
    echo "No ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Resident</title>
    <style>
        body {
            font-family: Arial;
            background: #eef1f5;
            padding: 30px;
        }

        .container {
            max-width: 950px;
            margin: auto;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .profile-section {
            display: flex;
            gap: 30px;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 25px;
        }

        .photo {
            width: 160px;
            height: 160px;
            border-radius: 12px;
            object-fit: cover;
            border: 4px solid #007bff;
        }

        .profile-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-id {
            color: #666;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .info-box label {
            display: block;
            font-size: 13px;
            color: #777;
            margin-bottom: 5px;
        }

        .info-box span {
            font-weight: bold;
            font-size: 15px;
        }

        .section-title {
            margin-top: 45px;
            font-size: 18px;
            font-weight: bold;
            border-left: 5px solid #007bff;
            padding-left: 10px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .status-ongoing {
            color: red;
            font-weight: bold;
        }

        .status-closed {
            color: green;
            font-weight: bold;
        }

        .btn-back {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 25px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-back:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- PROFILE SECTION -->
    <div class="profile-section">
        <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" 
             class="photo"
             onerror="this.src='default.png'">

        <div>
            <div class="profile-name">
                <?php echo htmlspecialchars($row['full_name']); ?>
            </div>
            <div class="profile-id">
                Resident ID: <?php echo htmlspecialchars($row['id']); ?>
            </div>
        </div>
    </div>

    <!-- PERSONAL INFO -->
    <div class="info-grid">

        <div class="info-box">
            <label>Gender</label>
            <span><?php echo htmlspecialchars($row['gender']); ?></span>
        </div>

        <div class="info-box">
            <label>Date of Birth</label>
            <span><?php echo htmlspecialchars($row['birth_date']); ?></span>
        </div>

        <div class="info-box">
            <label>Contact Number</label>
            <span><?php echo htmlspecialchars($row['contact_number']); ?></span>
        </div>

        <div class="info-box">
            <label>Email</label>
            <span><?php echo htmlspecialchars($row['email']); ?></span>
        </div>

        <div class="info-box">
            <label>Address</label>
            <span><?php echo htmlspecialchars($row['address']); ?></span>
        </div>

        <div class="info-box">
            <label>Created At</label>
            <span><?php echo htmlspecialchars($row['created_at']); ?></span>
        </div>

    </div>

    <!-- BARANGAY RECORD HISTORY -->
    <div class="section-title">Barangay Record History</div>

    <table>
        <tr>
            <th>ID</th>
            <th>Record Type</th>
            <th>Description</th>
            <th>Date Recorded</th>
            <th>Status</th>
        </tr>

        <?php
        if ($record_result->num_rows > 0) {
            while ($record = $record_result->fetch_assoc()) {

                $statusClass = "";
                if ($record['status'] == "Ongoing") {
                    $statusClass = "status-ongoing";
                } elseif ($record['status'] == "Closed") {
                    $statusClass = "status-closed";
                }

                echo "<tr>
                        <td>".htmlspecialchars($record['id'])."</td>
                        <td>".htmlspecialchars($record['record_type'])."</td>
                        <td>".htmlspecialchars($record['description'])."</td>
                        <td>".htmlspecialchars($record['date_recorded'])."</td>
                        <td class='".$statusClass."'>".htmlspecialchars($record['status'])."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No barangay records found.</td></tr>";
        }
        ?>
    </table>

    <a href="staff_resident.php" class="btn-back">Back to List</a>

</div>

</body>
</html>

<?php
$conn->close();
?>
