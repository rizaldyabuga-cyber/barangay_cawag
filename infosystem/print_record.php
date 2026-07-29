<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Record ID not found.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT br.*, r.full_name, r.gender, r.birth_date, r.address
    FROM barangay_records br
    LEFT JOIN residents r ON br.resident_id = r.id
    WHERE br.id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Record not found.");
}

$record = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Print Barangay Record</title>

<style>

body{
    font-family:"Times New Roman",serif;
    margin:40px;
}

.container{
    width:800px;
    margin:auto;
}

.center{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

table td{
    border:1px solid #000;
    padding:10px;
}

.print-btn{
    padding:10px 20px;
    background:#27ae60;
    color:#fff;
    border:none;
    cursor:pointer;
    margin-bottom:20px;
}

@media print{

.print-btn{
display:none;
}

}

</style>

</head>

<body>

<button class="print-btn" onclick="window.print()">Print</button>

<div class="container">

<div class="center">

<h3>Republic of the Philippines</h3>
<h3>Province of Zambales</h3>
<h3>Municipality of Subic</h3>
<h2>BARANGAY CAWAG</h2>

<h2>BARANGAY RECORD</h2>

</div>

<table>

<tr>
<td width="30%"><strong>Record ID</strong></td>
<td><?= $record['id']; ?></td>
</tr>

<tr>
<td><strong>Resident Name</strong></td>
<td><?= htmlspecialchars($record['full_name']); ?></td>
</tr>

<tr>
<td><strong>Gender</strong></td>
<td><?= htmlspecialchars($record['gender']); ?></td>
</tr>

<tr>
<td><strong>Birth Date</strong></td>
<td><?= date("F d, Y", strtotime($record['birth_date'])); ?></td>
</tr>

<tr>
<td><strong>Address</strong></td>
<td><?= htmlspecialchars($record['address']); ?></td>
</tr>

<tr>
<td><strong>Record Type</strong></td>
<td><?= htmlspecialchars($record['record_type']); ?></td>
</tr>

<tr>
<td><strong>Description</strong></td>
<td><?= nl2br(htmlspecialchars($record['description'])); ?></td>
</tr>

<tr>
<td><strong>Date Recorded</strong></td>
<td><?= date("F d, Y", strtotime($record['date_recorded'])); ?></td>
</tr>

<tr>
<td><strong>Status</strong></td>
<td><?= htmlspecialchars($record['status']); ?></td>
</tr>

</table>

<br><br><br>

<div style="width:300px; float:right; text-align:center;">

_________________________<br>
<b>Barangay Secretary</b>

</div>

</div>

</body>
</html>