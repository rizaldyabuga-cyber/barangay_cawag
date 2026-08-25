<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include "../db_connect.php";

$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

$stmt = $conn->prepare("
SELECT *
FROM audit_logs
WHERE username LIKE ?
OR action LIKE ?
OR module LIKE ?
OR description LIKE ?
ORDER BY created_at DESC
");

if(!$stmt){
    die("Query Error: ".$conn->error);
}

$keyword = "%".$search."%";

$stmt->bind_param(
    "ssss",
    $keyword,
    $keyword,
    $keyword,
    $keyword
);

$stmt->execute();

$logs = $stmt->get_result();

$total = $logs->num_rows;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

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

.container{
    max-width:1400px;
    margin:auto;
    background:#fff;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
    padding:30px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h2{
    color:#2c3e50;
}

.back-btn{
    background:#2c3e50;
    color:#fff;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;
    transition:.3s;
}

.back-btn:hover{
    background:#1a252f;
}

.search-box{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

.search-box input{
    flex:1;
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
}

.search-box button{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    background:#3498db;
    color:white;
    cursor:pointer;
}

.search-box button:hover{
    background:#2980b9;
}

.summary{
    margin-bottom:20px;
    font-size:16px;
    font-weight:bold;
    color:#2c3e50;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#3498db;
    color:white;
    padding:14px;
    text-align:left;
}

table td{
    padding:14px;
    border-bottom:1px solid #ddd;
}

table tr:nth-child(even){
    background:#f8f9fa;
}

table tr:hover{
    background:#eef6ff;
}

.action-buttons{
    margin-top:20px;
}

.btn{
    display:inline-block;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    margin-right:10px;
}

.print-btn{
    background:#27ae60;
}

.clear-btn{
    background:#e74c3c;
}

.print-btn:hover{
    background:#1f8d4d;
}

.clear-btn:hover{
    background:#c0392b;
}

@media print{

    .no-print{
        display:none;
    }

}

</style>

</head>

<body>

<div class="container">

<div class="header">

<h2>Activity Logs</h2>

<a href="index.php" class="back-btn no-print">
← Back to Utilities
</a>

</div>

<form method="GET" class="search-box no-print">

<input
type="text"
name="search"
placeholder="Search username or action..."
value="<?= htmlspecialchars($search) ?>">

<button type="submit">
Search
</button>

</form>

<div class="summary">

Total Logs: <?= $total ?>

</div>

<table>

<tr>

<th>ID</th>
<th>Username</th>
<th>Action</th>
<th>Module</th>
<th>Description</th>
<th>Date & Time</th>

</tr>

<?php
if($logs->num_rows>0){

while($row=$logs->fetch_assoc()){
?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['username']); ?></td>

<td><?= htmlspecialchars($row['action']); ?></td>

<td><?= htmlspecialchars($row['module']); ?></td>

<td><?= htmlspecialchars($row['description']); ?></td>

<td><?= $row['created_at']; ?></td>

</tr>

<?php
}

}else{
?>

<tr>

<td colspan="6" style="text-align:center;">
No activity logs found.
</td>

</tr>

<?php
}
?>

</table>

<div class="action-buttons no-print">

<a href="#" onclick="window.print()" class="btn print-btn">
🖨 Print Logs
</a>

<a href="clear_logs.php"
class="btn clear-btn"
onclick="return confirm('Are you sure you want to clear all logs?');">

🗑 Clear Logs

</a>

</div>

</div>

</body>
</html>