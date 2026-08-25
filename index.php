<?php
session_start();

if (isset($_SESSION['admin'])) {

    switch ($_SESSION['role']) {

        case 'admin':
            header("Location: dashboard.php");
            exit();

        case 'staff':
            header("Location: staff/staff_dashboard.php");
            exit();

        case 'captain':
            header("Location: captain/captain_dashboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta name="google-site-verification" content="P2z1uDLAvxggndhpufJn-DJgXGao7HqUsUarJynv0Yw" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Barangay Cawag Management System</title>

<meta name="description" content="Official Barangay Cawag Management System for authorized barangay personnel.">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:url('image/cawag.jpg') center center/cover no-repeat fixed;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
}

body::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.55);
}

.container{
    position:relative;
    width:90%;
    max-width:900px;
    padding:45px;
    border-radius:20px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    border:1px solid rgba(255,255,255,.25);
    box-shadow:0 20px 40px rgba(0,0,0,.35);
    text-align:center;
    color:#fff;
}

.container h1{
    font-size:38px;
    margin-bottom:10px;
}

.container h3{
    font-weight:400;
    margin-bottom:25px;
}

.description{
    font-size:17px;
    line-height:1.8;
    margin-bottom:35px;
}

.features{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
    margin-bottom:35px;
}

.feature{
    background:rgba(255,255,255,.12);
    padding:18px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,.15);
    font-size:15px;
}

.login-btn{
    display:inline-block;
    padding:15px 35px;
    background:#2ecc71;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    font-size:18px;
    font-weight:bold;
    transition:.3s;
}

.login-btn:hover{
    background:#27ae60;
    transform:translateY(-2px);
}

.footer{
    margin-top:35px;
    font-size:14px;
    color:#f1f1f1;
    line-height:1.8;
}

@media(max-width:768px){

.container{
    padding:30px 20px;
}

.container h1{
    font-size:30px;
}

.description{
    font-size:15px;
}

}

</style>

</head>

<body>

<div class="container">

<h1>Barangay Cawag</h1>

<h1>Management System</h1>

<p class="description">

Welcome to the official Barangay Cawag Management System.

This system is designed to securely manage resident records,
barangay certificates, clearances, incident records, and other
administrative services for Barangay Cawag, Subic, Zambales.

Access to this system is restricted to authorized personnel only.

</p>

<div class="features">

<div class="feature">
👨‍👩‍👧 Resident Management
</div>

<div class="feature">
📄 Barangay Certificates
</div>

<div class="feature">
📝 Barangay Records
</div>

<div class="feature">
📊 Audit Logs
</div>

<div class="feature">
👥 User Management
</div>

<div class="feature">
🔒 Secure Role-Based Access
</div>

</div>

<a href="login.php" class="login-btn">
Secure Login
</a>

<div class="footer">

<strong>Barangay Cawag</strong><br>

Subic, Zambales<br><br>

© <?php echo date("Y"); ?> Barangay Cawag Management System<br>

All Rights Reserved.

</div>

</div>

</body>
</html>