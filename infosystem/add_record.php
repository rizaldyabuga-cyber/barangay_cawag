<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch residents for dropdown
$residents_result = $conn->query("SELECT id, full_name FROM residents ORDER BY full_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $record_type = $_POST['record_type'];
    $description = $_POST['description'];
    $resident_id = $_POST['resident_id'];
    $date_recorded = $_POST['date_recorded'];

    $stmt = $conn->prepare("INSERT INTO barangay_records (record_type, description, resident_id, date_recorded, status) VALUES (?, ?, ?, ?, 'Ongoing')");
    $stmt->bind_param("ssis", $record_type, $description, $resident_id, $date_recorded);
    $stmt->execute();

    header("Location: barangay_record.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Barangay Record</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background:url('image/mountain.jpg') no-repeat center center fixed; background-size:cover; margin:0; padding:0; display:flex; justify-content:center; align-items:center; height:100vh; }
        .form-box { background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); padding:30px 40px; width:400px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.2); text-align:center; }
        h3 { margin-bottom:25px; color:#2c3e50; }
        input, select, textarea { width:100%; padding:12px 15px; margin:10px 0; border-radius:8px; border:1px solid #ccc; font-size:14px; transition:0.3s; }
        input:focus, select:focus, textarea:focus { border-color:#3498db; outline:none; }
        button { width:100%; padding:12px; margin-top:15px; background:#27ae60; color:white; border:none; border-radius:8px; font-size:15px; cursor:pointer; transition:0.3s; }
        button:hover { background:#219150; }
        a { display:inline-block; margin-top:15px; text-decoration:none; color:#3498db; transition:0.3s; }
        a:hover { color:#21618c; }
        textarea { resize: vertical; height:80px; }
    </style>
</head>
<body>

<div class="form-box">
    <h3>Add Barangay Record</h3>
    <form method="POST">
        <select name="record_type" required>
            <option value="">Select Record Type</option>
            <option value="Assault">Assault</option>
            <option value="Accident">Accident</option>
            <option value="Complaint">Complaint</option>
            <option value="Other">Other</option>
        </select>

        <textarea name="description" placeholder="Description" required></textarea>

        <select name="resident_id" required>
            <option value="">Select Resident</option>
            <?php
            if ($residents_result->num_rows > 0) {
                while ($resident = $residents_result->fetch_assoc()) {
                    echo "<option value='".$resident['id']."'>".$resident['full_name']."</option>";
                }
            }
            ?>
        </select>

        <input type="date" name="date_recorded" required>

        <button type="submit">Save</button>
    </form>
    <a href="barangay_record.php">← Back</a>
</div>

</body>
</html>
