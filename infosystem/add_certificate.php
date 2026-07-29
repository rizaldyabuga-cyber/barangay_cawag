<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Load residents
$residents = $conn->query("SELECT id, full_name FROM residents ORDER BY full_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $resident_id      = $_POST['resident_id'];
    $certificate_type = $_POST['certificate_type'];
    $purpose          = $_POST['purpose'];
    $issued_by        = $_POST['issued_by'];
    $date_issued      = $_POST['date_issued'];

    // Get resident name
    $stmt = $conn->prepare("SELECT full_name FROM residents WHERE id=?");
    $stmt->bind_param("i", $resident_id);
    $stmt->execute();

    $resident = $stmt->get_result()->fetch_assoc();
    $resident_name = $resident['full_name'];

    // Generate Control Number
$year = date("Y");

$result = $conn->query("SELECT MAX(id) AS last_id FROM certificates");
$row = $result->fetch_assoc();

$next_id = ($row['last_id'] ?? 0) + 1;

$control_no = "BC-" . $year . "-" . str_pad($next_id, 4, "0", STR_PAD_LEFT);

// Save Certificate
$stmt = $conn->prepare("
INSERT INTO certificates
(resident_name, certificate_type, purpose, issued_by, control_no, date_issued, resident_id)
VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssi",
    $resident_name,
    $certificate_type,
    $purpose,
    $issued_by,
    $control_no,
    $date_issued,
    $resident_id
);

if (!$stmt->execute()) {
    die($stmt->error);
}

$certificate_id = $conn->insert_id;

header("Location: certificates.php?id=" . $certificate_id);
exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Certificate</title>

<style>

body{
    font-family:Arial,Helvetica,sans-serif;
    background:#f4f6f9;
    margin:0;
}

.form-box{

    width:500px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.2);

}

h2{

    text-align:center;
    margin-bottom:20px;

}

label{

    font-weight:bold;

}

input,
select{

    width:100%;
    padding:10px;
    margin:8px 0 15px;
    border:1px solid #ccc;
    border-radius:5px;
    box-sizing:border-box;

}

button{

    width:100%;
    padding:12px;
    background:#3498db;
    color:#fff;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;

}

button:hover{

    background:#2980b9;

}

.back{

    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;

}

</style>

</head>

<body>

<div class="form-box">

<h2>Add Certificate</h2>

<form method="POST">

<label>Resident</label>

<select name="resident_id" required>

<option value="">Select Resident</option>

<?php while($row = $residents->fetch_assoc()) { ?>

<option value="<?= $row['id']; ?>">
    <?= htmlspecialchars($row['full_name']); ?>
</option>

<?php } ?>

</select>

<label>Certificate Type</label>

<select name="certificate_type" required>

<option value="Barangay Clearance">
Barangay Clearance
</option>

<option value="Certificate of Residency">
Certificate of Residency
</option>

<option value="Certificate of Indigency">
Certificate of Indigency
</option>

</select>

<label>Purpose</label>

<input
type="text"
name="purpose"
required>

<label>Issued By</label>

<input
type="text"
name="issued_by"
value="Barangay Captain"
required>

<label>Control Number</label>

<input
type="text"
value="Auto"
readonly>

<label>Date Issued</label>

<input
type="date"
name="date_issued"
required>

<button type="submit">
Add Certificate
</button>

</form>

<a class="back" href="certificates.php">
← Back to Certificates
</a>

</div>

</body>
</html>