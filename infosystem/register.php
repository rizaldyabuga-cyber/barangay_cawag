<?php
include "db_connect.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $contact_number = $_POST['contact_number'];
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("INSERT INTO residents (full_name, gender, birth_date, contact_number, address, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $gender, $birth_date, $contact_number, $address, $email);

    if ($stmt->execute()) {
        $message = "Resident registered successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barangay Registration</title>
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

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: -1;
        }

        .container {
            width: 420px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
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
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #43a047;
            transform: translateY(-2px);
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: #b6ffb6;
            font-size: 14px;
        }

        /*  ADMIN LOGIN button */
        .btn-admin {
            display: block;
            width: 100%;
            text-align: center;
            padding: 14px;
            margin-top: 10px;
            border-radius: 30px;
            background-color: #333;
            color: white;
            font-size: 16px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-admin:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Barangay Cawag Management System</h2>

    <?php if($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <input type="date" name="birth_date" required>
        <input type="text" name="contact_number" placeholder="Contact Number">
        <input type="text" name="address" placeholder="Address" required>
        <input type="email" name="email" placeholder="Email" required>

        <button type="submit">Register</button>
    </form>

    <!-- ADMIN LOGIN button -->
    <a href="dashboard.php" class="btn-admin">BACK TO DASHBOARD</a>
</div>

</body>
</html>
