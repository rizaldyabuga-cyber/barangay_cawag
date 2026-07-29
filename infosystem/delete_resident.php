<?php
include "db_connect.php";

$id = $_GET['id'];

if (isset($_POST['confirm'])) {
    $conn->query("DELETE FROM residents WHERE id = $id");
    header("Location: residents.php");
    exit();
}

$result = $conn->query("SELECT full_name FROM residents WHERE id = $id");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Resident</title>
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

        .box {
            width: 420px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            color: #fff;
            text-align: center;
        }

        h2 {
            margin-bottom: 15px;
        }

        p {
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .btn-group {
            display: flex;
            gap: 15px;
        }

        button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }

        .btn-cancel {
            background: #777;
            color: white;
        }

        .btn-cancel:hover {
            background: #555;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Delete Resident</h2>
    <p>Are you sure you want to delete <b><?= htmlspecialchars($row['full_name']) ?></b>?</p>

    <form method="POST">
        <div class="btn-group">
            <button type="submit" name="confirm" class="btn-delete">Yes, Delete</button>
            <a href="residents.php">
                <button type="button" class="btn-cancel">Cancel</button>
            </a>
        </div>
    </form>
</div>

</body>
</html>
