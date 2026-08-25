<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db_connect.php";


$search = "";
$type = "";
$date = "";


if(isset($_GET['search'])){
    $search = $_GET['search'];
}

if(isset($_GET['type'])){
    $type = $_GET['type'];
}

if(isset($_GET['date'])){
    $date = $_GET['date'];
}



$sql = "SELECT * FROM certificates WHERE 1=1";



if($search != ""){

    $sql .= "
AND (
    resident_name LIKE '%$search%'
    OR certificate_type LIKE '%$search%'
    OR purpose LIKE '%$search%'
    OR issued_by LIKE '%$search%'
    OR control_no LIKE '%$search%'
)
";

}



if($type != ""){

    $type = $conn->real_escape_string($type);

    $sql .= " AND certificate_type='$type' ";

}



if($date != ""){

    $date = $conn->real_escape_string($date);

    $sql .= " AND date_issued='$date' ";

}



$sql .= " ORDER BY id DESC";


$certificates = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Certificates</title>

    <style>
        body
       *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f4f6f9;
    padding:30px;
}

/* Main Container */
.container{
    max-width:1400px;
    margin:auto;
    background:#fff;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
    padding:30px;
}

/* Header */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h2{
    color:#2c3e50;
    font-size:28px;
}

/* Header Buttons */
.header-buttons{
    display:flex;
    gap:10px;
}

.btn{
    background:#2c3e50;
    color:#fff;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;
    transition:.3s;
}

.btn:hover{
    background:#1a252f;
}



/* Add Button */
.add-btn{
    display:inline-block;
    margin-bottom:20px;
    padding:10px 18px;
    background:#27ae60;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    transition:.3s;
}

.add-btn:hover{
    background:#219150;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:10px;
}

table th{
    background:#3498db;
    color:#fff;
    padding:14px;
}

table td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}

table tr:nth-child(even){
    background:#f8f9fa;
}

table tr:hover{
    background:#eef6ff;
}

/* Action Buttons */
.edit-btn,
.print-btn,
.delete-btn{
    display:inline-block;
    padding:7px 12px;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    margin:2px;
    transition:.3s;
}

.edit-btn{
    background:#f39c12;
}

.edit-btn:hover{
    background:#d68910;
}

.print-btn{
    background:#27ae60;
}

.print-btn:hover{
    background:#1e8449;
}

.delete-btn{
    background:#e74c3c;
}

.delete-btn:hover{
    background:#c0392b;
}
        


.filter-search-btn{

    background:#27ae60;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;

}


.filter-search-btn:hover{
    background:#219150;
}

}


.reset-btn{

    background:#e74c3c;
    color:white;
    text-decoration:none;
    padding:10px 20px;
    border-radius:8px;

}
        
        .filter-container{

    width:100%;
    margin-bottom:25px;
    display:flex;
    justify-content:flex-end;

}


.filter-container form{

    display:flex;
    gap:10px;
    flex-wrap:wrap;

}


.filter-container input,
.filter-container select{

    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;

}


.filter-container button{

    background:#27ae60;
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;

}


.filter-reset{

    background:#e74c3c;
    color:white;
    text-decoration:none;
    padding:10px 20px;
    border-radius:8px;

}
        
        
    </style>

</head>
<body>

<div class="container">


<div class="header">

    <h2>Certificates - Welcome, <?= $_SESSION['admin']; ?></h2>

    <a href="dashboard.php" class="btn">
        ← Back to Dashboard
    </a>

</div>


<div class="filter-container">

<form method="GET">

<input
type="text"
name="search"
placeholder="Search resident, certificate, control no."
value="<?= htmlspecialchars($search) ?>">

<select name="type">

<option value="">All Certificate Types</option>

<?php

$typeResult = $conn->query("
SELECT DISTINCT certificate_type
FROM certificates
ORDER BY certificate_type ASC
");

while($typeRow = $typeResult->fetch_assoc()){

?>

<option
value="<?= htmlspecialchars($typeRow['certificate_type']) ?>"
<?= $type == $typeRow['certificate_type'] ? "selected" : "" ?>>

<?= htmlspecialchars($typeRow['certificate_type']) ?>

</option>

<?php } ?>

</select>

<input
type="date"
name="date"
value="<?= htmlspecialchars($date) ?>">

<button type="submit" class="filter-search-btn">
Search
</button>

<a href="certificates.php" class="filter-reset">
Reset
</a>

</form>

</div>


<a href="add_certificate.php" class="add-btn">
    + Add Certificate
</a>


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