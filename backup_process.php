<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}

include "../db_connect.php";
?>

<!DOCTYPE html>
<html>
<head>

<title>Backup Database</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background:url('../image/cawag.jpg') no-repeat center center fixed;
    background-size:cover;
    display:flex;
    justify-content:center;
    align-items:center;
}

body::before{
    content:"";
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
}

.container{

    position:relative;
    z-index:1;

    width:500px;

    background:rgba(255,255,255,.15);
    backdrop-filter:blur(15px);

    padding:40px;

    border-radius:20px;

    text-align:center;

    color:white;

    box-shadow:0 20px 40px rgba(0,0,0,.3);

}

h2{

    margin-bottom:20px;

}

p{

    margin-bottom:30px;

}

.btn{

    display:inline-block;

    text-decoration:none;

    color:white;

    background:#27ae60;

    padding:14px 25px;

    border-radius:30px;

    transition:.3s;

}

.btn:hover{

    background:#219150;

}

.back{

    background:#2c3e50;

    margin-left:10px;

}

.back:hover{

    background:#1f2d3a;

}

</style>

</head>

<body>

<div class="container">

<h2>Database Backup</h2>

<p>
Click the button below to download a backup of the
<strong>Barangay Cawag Management System</strong> database.
</p>

<a href="backup_process.php" class="btn">
💾 Backup Database
</a>

<a href="index.php" class="btn back">
← Back
</a>

</div>

</body>
</html>