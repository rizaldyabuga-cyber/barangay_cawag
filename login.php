<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "db_connect.php";
require_once "utilities/audit_log.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");

    if (!$stmt) {
        die($conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {

            session_regenerate_id(true);

            $_SESSION['admin'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];

            logActivity(
                $conn,
                $_SESSION['admin'],
                "Login",
                "Authentication",
                null,
                "User logged into the system."
            );

            switch ($admin['role']) {

    case 'admin':
        $_SESSION['admin'] = $admin['username'];
        header("Location: dashboard.php");
        exit();

    case 'staff':
        $_SESSION['staff'] = $admin['username'];
        header("Location: staff/staff_dashboard.php"); 
        exit();

    case 'captain':
        $_SESSION['captain'] = $admin['username'];
        header("Location: captain/captain_dashboard.php");
        exit();
}

        } else {

            $message = "Invalid password.";

        }

    } else {

        $message = "Username not found.";

    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="google-site-verification" content="P2z1uDLAvxggndhpufJn-DJgXGao7HqUsUarJynv0Yw" />
   <title>Barangay Cawag Management System - Secure Login</title>
    <style>
       *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:url('image/cawag.jpg') center center/cover no-repeat fixed;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    position:relative;
}

body::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.45);
}

.login-box{
    position:relative;
    width:380px;
    padding:40px;
    border-radius:18px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    border:1px solid rgba(255,255,255,.25);
    box-shadow:0 20px 40px rgba(0,0,0,.35);
    animation:fade .7s ease;
}

@keyframes fade{
    from{
        opacity:0;
        transform:translateY(25px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.login-box h2{
    color:#fff;
    text-align:center;
    margin-bottom:30px;
    line-height:1.5;
}

input{
    width:100%;
    padding:13px 15px;
    border:none;
    border-radius:10px;
    outline:none;
    font-size:15px;
    margin-bottom:18px;
}

.password-wrapper{
    position:relative;
}

.password-wrapper input{
    padding-right:45px;
}

.toggle-password{
    position:absolute;
    right:15px;
    top:42%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
    color:#666;
}

button{
    width:100%;
    padding:13px;
    background:#2ecc71;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#27ae60;
    transform:translateY(-2px);
}

.message{
    background:#ffebee;
    color:#c62828;
    padding:12px;
    border-radius:8px;
    text-align:center;
    margin-bottom:20px;
    font-size:14px;
}

.login-box p{
    margin-top:18px;
    text-align:center;
}

.login-box a{
    color:#fff;
    text-decoration:none;
    font-size:14px;
}

.login-box a:hover{
    text-decoration:underline;
}
    </style>
</head>
<body>

<div class="login-box">
  <h2>
Barangay Cawag<br>
Management System
</h2>

<p style="color:white;text-align:center;margin:-15px 0 25px;font-size:15px;line-height:1.6;">
Official Secure Login Portal<br>
For Authorized Barangay Personnel Only
</p>
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>

        <button type="submit">Secure Login</button>
        <p style="margin-top:15px;">
    <a href="forgot_password.php">Forgot Password?</a>
</p>
    </form>
<hr style="margin:25px 0;border:0;border-top:1px solid rgba(255,255,255,.2);">

<p style="color:#fff;font-size:12px;text-align:center;line-height:1.6;">
Barangay Cawag, Subic, Zambales<br>
© <?php echo date("Y"); ?> Barangay Cawag Management System<br>
All Rights Reserved.
</p>
</div>

<script>
function togglePassword() {
    var password = document.getElementById("password");
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}
</script>

</body>
</html>
