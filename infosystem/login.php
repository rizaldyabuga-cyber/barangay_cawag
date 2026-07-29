<?php 
session_start(); 
include "db_connect.php";  

$message = "";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {     

    $username = trim($_POST['username']);     
    $password = $_POST['password'];      

    // 1️⃣ Get user by username ONLY
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");     
    $stmt->bind_param("s", $username);     
    $stmt->execute();     
    $result = $stmt->get_result();      

    if ($result->num_rows === 1) {         

        $admin = $result->fetch_assoc();

        // 2️⃣ Verify hashed password
        if (password_verify($password, $admin['password'])) {

    session_regenerate_id(true);

    $_SESSION['admin'] = $admin['username'];
    $_SESSION['role'] = $admin['role'];

    if ($admin['role'] == 'admin') {
        header("Location: dashboard.php");
    } 
    elseif ($admin['role'] == 'staff') {
        header("Location: staff/staff_dashboard.php");
    } 
    elseif ($admin['role'] == 'captain') {
        header("Location: captain/captain_dashboard.php");
    } 
    else {
        header("Location: login.php");
    }    

        } else {         
            $message = "Wrong username or password";     
        }

    } else {         
        $message = "Wrong username or password";     
    } 
} 
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>User Login</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background: url('image/cawag.jpg') no-repeat center center fixed;
    background-size: cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-box {
     background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 15px;
    width: 300px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    text-align: center;
}

input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

input[type="text"] {
    margin: 10px 0;
}

/* PASSWORD WRAPPER FIX */
.password-wrapper {
    position: relative;
    margin: 10px 0;
}

.password-wrapper input {
    padding-right: 40px;
}

.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 16px;
    color: #555;
}

button {
    width: 100%;
    padding: 10px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    margin-top: 10px;
}

button:hover {
    background: #45a049;
}

.back-btn {
    display: block;
    margin-top: 15px;
    padding: 10px;
    background: #555;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
}

.back-btn:hover {
    background: #333;
}

.message {
    color: red;
    margin-bottom: 10px;
}


        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            user-select: none;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Barangay Cawag Management System<br><br>User Login</h2>

    <?php if ($message) echo "<div class='message'>$message</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>

        <button type="submit">Login</button>
    </form>

    <a href="registration.php" class="back-btn">REGISTER</a>
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
