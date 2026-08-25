<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "../db_connect.php";

$message = "";

$username = $_SESSION['admin'];

if (isset($_POST['change'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get current user
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (!password_verify($current_password, $user['password'])) {

            $message = "Current password is incorrect.";

        } elseif ($new_password != $confirm_password) {

            $message = "New passwords do not match.";

        } else {

            $hashed = password_hash($new_password, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE admins SET password=? WHERE username=?");
            $update->bind_param("ss", $hashed, $username);

            if ($update->execute()) {

                $message = "Password changed successfully.";

            } else {

                $message = "Failed to change password.";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>

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

.form-box{
    position:relative;
    z-index:1;
    width:450px;
    padding:40px;
    border-radius:20px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(15px);
    color:#fff;
    box-shadow:0 20px 40px rgba(0,0,0,.35);
}

h2{
    text-align:center;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    margin:12px 0;
    outline:none;
}

button{
    width:100%;
    padding:13px;
    border:none;
    border-radius:30px;
    background:#4CAF50;
    color:white;
    cursor:pointer;
    font-size:15px;
    transition:.3s;
}

button:hover{
    background:#43a047;
}

.back-btn{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:white;
    background:#2c3e50;
    padding:12px;
    border-radius:30px;
}

.back-btn:hover{
    background:#1f2d3a;
}

.message{
    text-align:center;
    margin-bottom:15px;
    font-weight:bold;
    color:#ffe082;
}

</style>

</head>
<body>

<div class="form-box">

<h2>Change Password</h2>

<?php
if($message){
    echo "<div class='message'>$message</div>";
}
?>

<form method="POST">

<input type="password" name="current_password" placeholder="Current Password" required>

<input type="password" name="new_password" placeholder="New Password" required>

<input type="password" name="confirm_password" placeholder="Confirm New Password" required>

<button type="submit" name="change">
Change Password
</button>

</form>

<a href="../dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

</body>
</html>