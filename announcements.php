<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$announcements = $conn->query("
    SELECT *
    FROM announcements
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Announcements</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

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

/* Container */

.container{
    max-width:1400px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
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
}

.btn{

    background:#2c3e50;
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;
    transition:.3s;

}

.btn:hover{

    background:#1f2d3a;

}

.add-btn{

    display:inline-block;
    margin-bottom:20px;
    background:#27ae60;
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;

}

.add-btn:hover{

    background:#219150;

}

/* Table */

table{

    width:100%;
    border-collapse:collapse;

}

table th{

    background:#3498db;
    color:white;
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

/* Message column */

.message{

    max-width:350px;
    text-align:left;

}

/* Status */

.active{

    color:green;
    font-weight:bold;

}

.expired{

    color:red;
    font-weight:bold;

}

/* Buttons */

.edit-btn,
.delete-btn{

    display:inline-block;
    padding:7px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    margin:2px;

}

.edit-btn{

    background:#f39c12;

}

.edit-btn:hover{

    background:#d68910;

}

.delete-btn{

    background:#e74c3c;

}

.delete-btn:hover{

    background:#c0392b;

}

</style>

</head>

<body>

<div class="container">

<div class="header">

<h2>📢 Announcements</h2>

<a href="dashboard.php" class="btn">
Dashboard
</a>

</div>

<a href="add_announcement.php" class="add-btn">
+ Add Announcement
</a>

<table>

<tr>

<th>ID</th>
<th>Title</th>
<th>Message</th>
<th>Expiry Date</th>
<th>Status</th>
<th>Posted By</th>
<th>Date Posted</th>
<th>Actions</th>

</tr>

<?php while($row = $announcements->fetch_assoc()) { ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['title']); ?></td>

<td class="message">

<?= htmlspecialchars(substr($row['message'],0,80)); ?>...

</td>

<td>

<?= date("F d, Y", strtotime($row['expiry_date'])); ?>

</td>

<td>

<?php

if(strtotime($row['expiry_date']) >= strtotime(date("Y-m-d"))){

    echo "<span class='active'>Active</span>";

}else{

    echo "<span class='expired'>Expired</span>";

}

?>

</td>

<td>

<?= htmlspecialchars($row['posted_by']); ?>

</td>

<td>

<?= date("F d, Y", strtotime($row['created_at'])); ?>

</td>

<td>

<a
href="edit_announcement.php?id=<?= $row['id']; ?>"
class="edit-btn">

Edit

</a>

<a
href="delete_announcement.php?id=<?= $row['id']; ?>"
class="delete-btn"
onclick="return confirm('Delete this announcement?');">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>