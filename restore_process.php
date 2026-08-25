<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Restore Database</title>

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

    width:550px;

    background:rgba(255,255,255,.15);

    backdrop-filter:blur(15px);

    border-radius:20px;

    padding:40px;

    text-align:center;

    color:white;

}

h2{

    margin-bottom:20px;

}

p{

    margin-bottom:25px;

}

input[type=file]{

    width:100%;

    padding:12px;

    background:white;

    border-radius:10px;

    margin-bottom:20px;

}

button{

    border:none;

    background:#3498db;

    color:white;

    padding:14px 25px;

    border-radius:30px;

    cursor:pointer;

    font-size:15px;

}

button:hover{

    background:#2980b9;

}

.back{

    display:inline-block;

    margin-left:10px;

    background:#2c3e50;

    color:white;

    text-decoration:none;

    padding:14px 25px;

    border-radius:30px;

}

.back:hover{

    background:#1a252f;

}

.warning{

    margin-top:20px;

    color:#ffe082;

    font-size:14px;

}

</style>

</head>

<body>

<div class="container">

<h2>Restore Database</h2>

<p>
Select your SQL backup file.
</p>

    <?php
if(isset($_GET['success'])){
    echo "<div style='background:#4CAF50;color:white;padding:12px;border-radius:8px;margin-bottom:20px;'>
    Database restored successfully.
    </div>";
}
?>
    
<form
action="restore_process.php"
method="POST"
enctype="multipart/form-data">

<input
type="file"
name="sql_file"
accept=".sql"
required>

<button
type="submit"
onclick="return confirm('Restoring will overwrite your current database. Continue?')">

Restore Database

</button>

<a href="index.php" class="back">

Back

</a>

</form>

<p class="warning">

⚠ Always create a backup before restoring.

</p>

</div>

</body>
</html>