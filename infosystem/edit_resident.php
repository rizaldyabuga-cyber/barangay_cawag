<?php
include "db_connect.php";

if (!isset($_GET['id'])) {
    header("Location: residents.php");
    exit();
}

$id = intval($_GET['id']);

/* =====================
   UPDATE RESIDENT
===================== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    $stmt = $conn->prepare(
        "UPDATE residents 
         SET full_name=?, gender=?, birth_date=?, contact_number=?, address=?, email=? 
         WHERE id=?"
    );

    $stmt->bind_param(
        "ssssssi",
        $full_name,
        $gender,
        $birth_date,
        $contact_number,
        $address,
        $email,
        $id
    );

    $stmt->execute();
    header("Location: residents.php");
    exit();
}

/* =====================
   FETCH RESIDENT
===================== */
$result = $conn->query("SELECT * FROM residents WHERE id = $id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Resident</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: url('image/mountain.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
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
            background: rgba(255,255,255,0.9);
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: #2196F3;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1976D2;
            transform: translateY(-2px);
        }

        .back {
            text-align: center;
            margin-top: 15px;
        }

        .back a {
            color: #fff;
            text-decoration: none;
            opacity: 0.8;
        }

        .back a:hover {
            opacity: 1;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Resident</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <input type="text" name="full_name"
               value="<?= htmlspecialchars($row['full_name']) ?>" required>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?= $row['gender']=="Male" ? "selected" : "" ?>>Male</option>
            <option value="Female" <?= $row['gender']=="Female" ? "selected" : "" ?>>Female</option>
        </select>

        <input type="date" name="birth_date"
               value="<?= $row['birth_date'] ?>" required>

        <input type="text" name="contact_number"
               value="<?= htmlspecialchars($row['contact_number']) ?>">

        <input type="text" name="address"
               value="<?= htmlspecialchars($row['address']) ?>" required>

        <input type="email" name="email"
               value="<?= htmlspecialchars($row['email']) ?>" required>

        <button type="submit">Update Resident</button>
    </form>

    <div class="back">
        <a href="residents.php">← Back to Residents</a>
    </div>
</div>

</body>
</html>
