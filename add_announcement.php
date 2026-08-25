<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $announcement = trim($_POST['message']);
    $expiry_date = $_POST['expiry_date'];
    $posted_by = $_SESSION['admin'];

    $stmt = $conn->prepare("
       INSERT INTO announcements
(title, priority, message, expiry_date, posted_by)
VALUES (?, ?, ?, ?, ?)
    ");

    $priority = $_POST['priority'];

$stmt->bind_param(
    "sssss",
    $title,
    $priority,
    $announcement,
    $expiry_date,
    $posted_by
);

    if ($stmt->execute()) {

        header("Location: announcements.php");
        exit();

    } else {

        $message = "Failed to save announcement.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Announcement</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f4f6f9;
    padding:30px;
}

.container{

    max-width:700px;
    margin:auto;
    background:white;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);

}

h2{

    text-align:center;
    margin-bottom:25px;
    color:#2c3e50;

}

.success{

    background:#e74c3c;
    color:white;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
    text-align:center;

}

label{

    display:block;
    margin-top:18px;
    margin-bottom:8px;
    font-weight:600;
    color:#2c3e50;

}

input,
textarea{

    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:15px;
    outline:none;

}
    
    input,
textarea,
select{

    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:10px;
    outline:none;
    font-size:15px;

}

textarea{

    resize:vertical;
    min-height:150px;

}

.buttons{

    margin-top:30px;
    display:flex;
    justify-content:space-between;

}

.save{

    background:#27ae60;
    color:white;
    border:none;
    padding:12px 28px;
    border-radius:30px;
    cursor:pointer;
    font-size:15px;

}

.save:hover{

    background:#219150;

}

.back{

    text-decoration:none;
    background:#2c3e50;
    color:white;
    padding:12px 28px;
    border-radius:30px;

}

.back:hover{

    background:#1f2d3a;

}

.info{

    margin-top:20px;
    background:#eef6ff;
    padding:12px;
    border-radius:8px;
    color:#2c3e50;

}

</style>

</head>

<body>

<div class="container">

<h2>📢 Add Announcement</h2>

<?php if($message!=""){ ?>

<div class="success">

<?= $message ?>

</div>

<?php } ?>

<form method="POST">

<label>Title</label>

<input
type="text"
name="title"
required>

    <label>Priority</label>

<select name="priority" required>

    <option value="Normal">🔵 Normal</option>

    <option value="Important">🟡 Important</option>

    <option value="Urgent">🔴 Urgent</option>

</select>
    
<label>Announcement</label>

<textarea
name="message"
required></textarea>

<label>Expiry Date</label>

<input
type="date"
name="expiry_date"
required>

<div class="info">

<strong>Posted By:</strong>
<?= htmlspecialchars($_SESSION['admin']); ?>

</div>

<div class="buttons">

<button
class="save"
type="submit">

📢 Post Announcement

</button>

<a
href="announcements.php"
class="back">

← Back

</a>

</div>

</form>

</div>

</body>
</html>