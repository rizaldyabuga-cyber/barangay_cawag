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
// Fetch all records with resident names
$search = $_GET['search'] ?? "";
$type = $_GET['type'] ?? "";
$status = $_GET['status'] ?? "";

$sql = "
SELECT br.*, r.full_name
FROM barangay_records br
LEFT JOIN residents r ON br.resident_id = r.id
WHERE 1=1
";

if($search != ""){

    $search = $conn->real_escape_string($search);

    $sql .= "
    AND(
        r.full_name LIKE '%$search%'
        OR br.record_type LIKE '%$search%'
        OR br.description LIKE '%$search%'
    )
    ";

}

if($type != ""){

    $type = $conn->real_escape_string($type);

    $sql .= " AND br.record_type='$type' ";

}

if($status != ""){

    $status = $conn->real_escape_string($status);

    $sql .= " AND br.status='$status' ";

}

$sql .= " ORDER BY br.date_recorded DESC";

$records = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Barangay Record</title>
    <style>
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
    background:#1e8449;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

table th{
    background:#3498db;
    color:#fff;
    padding:14px;
    text-align:center;
}

table td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #ddd;
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
    border-radius:6px;
    text-decoration:none;
    color:#fff;
    margin:2px;
    transition:.3s;
}

.edit-btn{
    background:#3498db;
}

.edit-btn:hover{
    background:#2980b9;
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
        
        /* Search & Filter */

.filter-container{

    display:flex;
    justify-content:flex-end;
    margin-bottom:25px;

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
    outline:none;

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

.filter-reset{

    background:#e74c3c;
    color:white;
    text-decoration:none;
    padding:10px 20px;
    border-radius:8px;

}

.filter-reset:hover{

    background:#c0392b;

}
        
    </style>
</head>
<body>

<div class="header">
    <h2>Barangay Records - Welcome, <?= $_SESSION['admin'] ?></h2>

    <div class="header-buttons">
        <a href="dashboard.php" class="btn">Dashboard</a>
    </div>
</div>
<div class="filter-container">

<form method="GET">

<input
type="text"
name="search"
placeholder="Search resident, type or description"
value="<?= htmlspecialchars($search) ?>">

<select name="type">

<option value="">All Record Types</option>

<?php

$typeResult = $conn->query("
SELECT DISTINCT record_type
FROM barangay_records
ORDER BY record_type
");

while($row = $typeResult->fetch_assoc()){

?>

<option
value="<?= htmlspecialchars($row['record_type']) ?>"
<?= $type == $row['record_type'] ? "selected" : "" ?>>

<?= htmlspecialchars($row['record_type']) ?>

</option>

<?php } ?>

</select>

<select name="status">

<option value="">All Status</option>

<?php

$statusResult = $conn->query("
SELECT DISTINCT status
FROM barangay_records
ORDER BY status
");

while($row = $statusResult->fetch_assoc()){

?>

<option
value="<?= htmlspecialchars($row['status']) ?>"
<?= $status == $row['status'] ? "selected" : "" ?>>

<?= htmlspecialchars($row['status']) ?>

</option>

<?php } ?>

</select>

<button class="filter-search-btn">
Search
</button>

<a href="barangay_record.php" class="filter-reset">
Reset
</a>

</form>

</div>
<div class="container">

    <!-- ADD BUTTON -->
    <a href="add_record.php" class="add-btn">+ Add Record</a>

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
                        <a href="edit_record.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                        <a href="print_record.php?id=<?= $row['id']?>" class="print-btn" target="blank">Print</a>
                        <a href="delete_record.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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
