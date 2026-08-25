<?php
include "db_connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // ✅ HASH THE PASSWORD
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO admins (username, email,password, role)
         VALUES (?, ?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sss",
        $username,
        $hashed_password, // ✅ SAVE HASHED PASSWORD
        $role
    );

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $message = "Error registering user.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: url('image/cawag.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Dark overlay */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: -1;
        }

        .form-container {
            width: 420px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            color: #fff;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }

        label {
            font-size: 14px;
            opacity: 0.9;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            margin: 8px 0 16px;
            border-radius: 10px;
            border: none;
            outline: none;
            background: rgba(255,255,255,0.85);
            font-size: 14px;
        }

        input:focus, select:focus {
            background: #fff;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #43a047;
            transform: translateY(-2px);
        }
        
        .back-btn{
    display:block;
    width:100%;
    text-align:center;
    margin-top:15px;
    padding:14px;
    background:#2c3e50;
    color:#fff;
    text-decoration:none;
    border-radius:30px;
    transition:.3s;
}

.back-btn:hover{
    background:#1a252f;
}
    </style>
</head>
<body>

<div class="form-container">
    <h2>User Registration</h2>

    <form method="POST">
        <label>User Name</label>
        <input type="text" name="username" placeholder="Juan Dela Cruz" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="text" name="password" placeholder="123" required>
        <label>Role</label>
        <input type="text" name="role" required>

       
        <button type="submit">Register</button>
         <a href="dashboard.php" class="back-btn">BACK</a>
    </form>
    
      

</body>
</html>
