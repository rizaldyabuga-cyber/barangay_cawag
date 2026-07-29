<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Certificate ID not found.");
}

$id = intval($_GET['id']);

// Get certificate information
$sql = $conn->query("
    SELECT *
    FROM certificates
    WHERE id = $id
");

if ($sql->num_rows == 0) {
    die("Certificate not found.");
}

$certificate = $sql->fetch_assoc();

// Get resident information
$resident = null;

if (!empty($certificate['resident_id'])) {

    $residentQuery = $conn->query("
        SELECT *
        FROM certificates
        WHERE id = {$certificate['resident_id']}
    ");

    if ($residentQuery->num_rows > 0) {
        $resident = $residentQuery->fetch_assoc();
    }
}

$name = $resident ? $resident['full_name'] : $certificate['resident_name'];

$gender = $resident ? $resident['gender'] : "";

$birthdate = $resident
    ? date("F d, Y", strtotime($resident['birth_date']))
    : "";

$address = $resident
    ? $resident['address']
    : "Barangay Cawag, Subic, Zambales";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Print Certificate</title>

<style>

body{
    font-family: "Times New Roman", serif;
    margin:40px;
    line-height:1.8;
}

.container{
    width:800px;
    margin:auto;
}

.center{
    text-align:center;
}

h2,h3{
    margin:0;
}

.print-btn{
    background:#27ae60;
    color:white;
    padding:10px 20px;
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

<button class="print-btn" onclick="window.print()">
Print Certificate
</button>

<div class="container">

<div class="center">

<h3>Republic of the Philippines</h3>
<h3>Province of Zambales</h3>
<h3>Municipality of Subic</h3>
<h2>BARANGAY CAWAG</h2>

<p><strong><?php echo strtoupper($certificate['certificate_type']); ?></strong></p>

</div>

<br>

<p><strong>TO WHOM IT MAY CONCERN:</strong></p>

<p style="text-indent:60px; text-align:justify;">

This is to certify that
<strong><?= strtoupper($name) ?></strong>,
<?= $gender ?>,
born on
<strong><?= $birthdate ?></strong>,
and presently residing at
<strong><?= $address ?></strong>,
is a bona fide resident of Barangay Cawag,
Subic, Zambales.

</p>

<p style="text-indent:60px; text-align:justify;">

This <?= strtolower($certificate['certificate_type']) ?> is issued upon
the request of the above-named person for whatever legal purpose it may serve.

</p>

<br><br>

<p>

Issued this
<strong>
<?php
echo date("F d, Y", strtotime($certificate['date_issued']));
?>
</strong>

at Barangay Cawag, Subic, Zambales.

</p>

<br><br><br>

<div style="width:300px; float:right; text-align:center;">

_________________________<br>
<b>Barangay Captain</b>

</div>

</div>

</body>
</html>