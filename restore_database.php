<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Utilities</title>

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
}

body::before{
    content:"";
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    z-index:-1;
}

/* Header */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 35px;
    color:white;
}

.header h2{
    font-weight:600;
}

.back-btn{
    background:#2c3e50;
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;
    transition:.3s;
}

.back-btn:hover{
    background:#1f2d3a;
}

/* Cards */

.container{

    max-width:1200px;
    margin:40px auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;

}

.card{

    background:rgba(255,255,255,.15);
    backdrop-filter:blur(15px);
    border-radius:20px;
    padding:35px;
    color:white;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.35);
    transition:.3s;

}

.card:hover{

    transform:translateY(-8px);

}

.card h3{

    margin-bottom:15px;

}

.card .icon{

    font-size:55px;
    margin-bottom:20px;

}

.card a{

    display:inline-block;
    margin-top:15px;
    padding:10px 20px;
    border-radius:30px;
    text-decoration:none;
    background:#4CAF50;
    color:white;

}

.card a:hover{

    background:#43a047;

}

    </style>

</head>
<body>

<div class="header">

<h2>Utilities</h2>

<a href="../dashboard.php" class="back-btn">
← Back to Dashboard
</a>

</div>

<div class="container">

<div class="card">

<div class="icon">🔒</div>

<h3>Change Password</h3>

<a href="change_password.php">
Open
</a>

</div>

<div class="card">

<div class="icon">📋</div>

<h3>Activity Logs</h3>

<a href="activity_logs.php">
Open
</a>

</div>

<div class="card">

<div class="icon">💾</div>

<h3>Backup Database</h3>

<a href="backup_database.php">
Open
</a>

</div>

<div class="card">

<div class="icon">♻️</div>

<h3>Restore Database</h3>

<a href="restore_database.php">
Open
</a>

</div>

<div class="card">

<div class="icon">⚙️</div>

<h3>System Settings</h3>

<a href="system_settings.php">
Open
</a>

</div>

<div class="card">

<div class="icon">ℹ️</div>

<h3>System Information</h3>

<a href="about.php">Open</a>
 <h3>About System</h3>
    <p>View system information and developer details.</p>
</div>

</div>

</body>
</html>