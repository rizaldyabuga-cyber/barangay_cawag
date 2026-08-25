<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

include "../db_connect.php";
include "../includes/system_info.php";
?>

<!DOCTYPE html>
<html>
<head>

<title>About System</title>

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
    z-index:-1;
}

.container{

    width:700px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(15px);
    border-radius:20px;
    padding:40px;
    color:white;
    text-align:center;
    box-shadow:0 20px 40px rgba(0,0,0,.35);

}

.logo{

    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:15px;
    border:3px solid white;
    margin-bottom:20px;

}

h2{

    margin-bottom:10px;

}

.version{

    color:#ffd54f;
    font-weight:bold;
    margin-bottom:25px;

}

.info{

    text-align:left;
    margin-top:20px;
    line-height:2;

}

.info strong{

    display:inline-block;
    width:170px;

}

.back{

    display:inline-block;
    margin-top:35px;
    padding:12px 30px;
    background:#2c3e50;
    color:white;
    text-decoration:none;
    border-radius:30px;

}

.back:hover{

    background:#1f2d3a;

}

.footer{

    margin-top:30px;
    font-size:13px;
    color:#ddd;

}

</style>

</head>

<body>

<div class="container">

<?php if(!empty($system['logo'])){ ?>

<img src="../<?= htmlspecialchars($system['logo']); ?>" class="logo">

<?php } ?>

<h2><?= htmlspecialchars($system['system_name']); ?></h2>

<div class="version">
Version 1.0
</div>

<div class="info">

<p><strong>Barangay:</strong> <?= htmlspecialchars($system['barangay_name']); ?></p>

<p><strong>Municipality:</strong> <?= htmlspecialchars($system['municipality']); ?></p>

<p><strong>Province:</strong> <?= htmlspecialchars($system['province']); ?></p>

<p><strong>Barangay Captain:</strong> <?= htmlspecialchars($system['captain_name']); ?></p>

<p><strong>Secretary:</strong> <?= htmlspecialchars($system['secretary_name']); ?></p>

<p><strong>Treasurer:</strong> <?= htmlspecialchars($system['treasurer_name']); ?></p>

<p><strong>Developer:</strong> Rizaldy H. Abuga</p>

<p><strong>School:</strong> Kolehiyo ng Subic</p>

<p><strong>Technology:</strong> PHP, MySQL, HTML, CSS, JavaScript</p>

<p><strong>Academic Year:</strong> 2026–2027</p>

</div>

<a href="index.php" class="back">
← Back to Utilities
</a>

<div class="footer">

© <?= date("Y"); ?> <?= htmlspecialchars($system['system_name']); ?><br>
All Rights Reserved.

</div>

</div>

</body>
</html>