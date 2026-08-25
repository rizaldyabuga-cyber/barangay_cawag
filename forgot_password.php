<?php
session_start();
include "db_connect.php";
require "send_otp_api.php";

$message = "";

if(isset($_POST['send'])){

    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM admins WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $otp = rand(100000,999999);

        $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Remove old OTP
        $delete = $conn->prepare("DELETE FROM password_resets WHERE email=?");
        $delete->bind_param("s",$email);
        $delete->execute();

        // Save new OTP
        $insert = $conn->prepare("INSERT INTO password_resets(email,otp,expires_at) VALUES(?,?,?)");
        $insert->bind_param("sss",$email,$otp,$expires);
        $insert->execute();

        // Send Email
        if(sendOTP($email,$otp)){

            $_SESSION['reset_email']=$email;

            header("Location: verify_otp.php");
            exit();

        }else{

            $message="Failed to send OTP.";

        }

    }else{

        $message="Email not found.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:url('image/cawag.jpg') no-repeat center center fixed;
            background-size:cover;
        }

        body::before{
            content:"";
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.45);
            z-index:-1;
        }

        .container{
            width:400px;
            padding:35px;
            background:rgba(255,255,255,.15);
            backdrop-filter:blur(15px);
            border-radius:20px;
            box-shadow:0 15px 35px rgba(0,0,0,.3);
            color:#fff;
        }

        h2{
            text-align:center;
            margin-bottom:25px;
        }

        input{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            margin:15px 0;
            outline:none;
            font-size:15px;
        }

        button{
            width:100%;
            padding:12px;
            background:#27ae60;
            border:none;
            border-radius:8px;
            color:#fff;
            cursor:pointer;
            font-size:15px;
            transition:.3s;
        }

        button:hover{
            background:#219150;
        }

        .back-btn{
            display:block;
            text-align:center;
            margin-top:15px;
            padding:12px;
            background:#2c3e50;
            color:#fff;
            text-decoration:none;
            border-radius:8px;
            transition:.3s;
        }

        .back-btn:hover{
            background:#1a252f;
        }

        .message{
            background:#e74c3c;
            padding:10px;
            border-radius:6px;
            text-align:center;
            margin-bottom:15px;
        }
    </style>

</head>
<body>

<div class="container">

    <h2>Forgot Password</h2>

    <?php if($message!=""){ ?>
        <div class="message"><?= $message ?></div>
    <?php } ?>

   <form method="POST">

<label>Email Address</label>

<input
type="email"
name="email"
required>

<button
type="submit"
name="send">
Send OTP
</button>

</form>
    <a href="login.php" class="back-btn">
        ← Back to Login
    </a>

</div>

</body>
</html>