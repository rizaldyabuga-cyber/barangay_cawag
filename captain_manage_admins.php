<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing record
$stmt = $conn->prepare("SELECT * FROM barangay_records WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

// Fetch all residents for dropdown
$residents_result = $conn->query("SELECT id, full_name FROM residents ORDER BY full_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $record_type = $_POST['record_type'];
    $description = $_POST['description'];
    $resident_id = $_POST['resident_id'];
    $date_recorded = $_POST['date_recorded'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE barangay_records SET record_type=?, description=?, resident_id=?, date_recorded=?, status=? WHERE id=?");
    $update->bind_param("ssissi", $record_type, $description, $resident_id, $date_recorded, $status, $id);
    $update->execute();

    header("Location: barangay_record.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barangay Record</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background:url('image/mountain.jpg') no-repeat center center fixed; background-size:cover; margin:0; padding:0; display:flex; justify-content:center; align-items:center; height:100vh; }
        .form-box { background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); padding:30px 40px; width:400px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.2); text-align:center; }
        h3 { margin-bottom:25px; color:#2c3e50; }
        input, select, textarea { width:100%; padding:12px 15px; margin:10px 0; border-radius:8px; border:1px solid #ccc; font-size:14px; transition:0.3s; }
        input:focus, select:focus, textarea:focus { border-color:#3498db; outline:none; }
        button { width:100%; padding:12px; margin-top:15px; background:#f39c12; color:white; border:none; border-radius:8px; font-size:15px; cursor:pointer; transition:0.3s; }
        button:hover { background:#d68910; }
        a { display:inline-block; margin-top:15px; text-decoration:none; color:#3498db; transition:0.3s; }
        a:hover { color:#21618c; }
        textarea { resize: vertical; height:80px; }
    </style>
</head>
<body>

<div class="form-box">
    <h3>Edit Barangay Record</h3>
    <form method="POST">

        <!-- Record Type -->
        <select name="record_type" required>
            <option value="Assault" <?= $record['record_type']=="Assault"?'selected':'' ?>>Assault</option>
            <option value="Accident" <?= $record['record_type']=="Accident"?'selected':'' ?>>Accident</option>
            <option value="Complaint" <?= $record['record_type']=="Complaint"?'selected':'' ?>>Complaint</option>
            <option value="Other" <?= $record['record_type']=="Other"?'selected':'' ?>>Other</option>
        </select>

        <!-- Description -->
        <textarea name="description" required><?= htmlspecialchars($record['description']) ?></textarea>

        <!-- Resident Dropdown -->
        <select name="resident_id" required>
            <option value="">Select Resident</option>
            <?php
            if ($residents_result->num_rows > 0) {
                while ($resident = $residents_result->fetch_assoc()) {
                    $selected = ($resident['id'] == $record['resident_id']) ? "selected" : "";
                    echo "<option value='".$resident['id']."' $selected>".$resident['full_name']."</option>";
                }
            }
            ?>
        </select>

        <!-- Date Recorded -->
        <input type="date" name="date_recorded" value="<?= $record['date_recorded'] ?>" required>

        <!-- Status -->
        <select name="status" required>
            <option value="Ongoing" <?= $record['status']=="Ongoing"?'selected':'' ?>>Ongoing</option>
            <option value="Closed" <?= $record['status']=="Closed"?'selected':'' ?>>Closed</option>
        </select>

        <button type="submit">Update</button>
    </form>
    <a href="captain_barangay_record.php">← Back</a>
</div>

</body>
</html>
