<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Announcement ID not found.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM announcements WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Announcement not found.");
}

$announcement = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $priority = $_POST['priority'];
    $content = trim($_POST['message']);
    $expiry_date = $_POST['expiry_date'];

    $stmt = $conn->prepare("
        UPDATE announcements
        SET
            title=?,
            priority=?,
            message=?,
            expiry_date=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssssi",
        $title,
        $priority,
        $content,
        $expiry_date,
        $id
    );

    if ($stmt->execute()) {

        header("Location: announcements.php");
        exit();

    } else {

        $message = "Failed to update announcement.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Announcement</title>

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

.error{
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

.info{
    margin-top:20px;
    padding:12px;
    background:#eef6ff;
    border-radius:8px;
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
    background:#2c3e50;
    color:white;
    text-decoration:none;
    padding:12px 28px;
    border-radius:30px;
}

.back:hover{
    background:#1f2d3a;
}

</style>

</head>

<body>

<div class="container">

<h2>✏️ Edit Announcement</h2>

<?php if($message!=""){ ?>

<div class="error">

<?= $message ?>

</div>

<?php } ?>

<form method="POST">

<label>Title</label>

<input
type="text"
name="title"
value="<?= htmlspecialchars($announcement['title']) ?>"
required>

<label>Priority</label>

<select name="priority" required>

<option value="Normal"
<?= $announcement['priority']=="Normal" ? "selected" : "" ?>>
🔵 Normal
</option>

<option value="Important"
<?= $announcement['priority']=="Important" ? "selected" : "" ?>>
🟡 Important
</option>

<option value="Urgent"
<?= $announcement['priority']=="Urgent" ? "selected" : "" ?>>
🔴 Urgent
</option>

</select>

<label>Announcement</label>

<textarea
name="message"
required><?= htmlspecialchars($announcement['message']) ?></textarea>

<label>Expiry Date</label>

<input
type="date"
name="expiry_date"
value="<?= $announcement['expiry_date'] ?>"
required>

<div class="info">

<strong>Posted By:</strong>
<?= htmlspecialchars($announcement['posted_by']) ?>

<br><br>

<strong>Date Posted:</strong>
<?= date("F d, Y", strtotime($announcement['created_at'])) ?>

</div>

<div class="buttons">

<button class="save" type="submit">

💾 Update Announcement

</button>

<a href="announcements.php" class="back">

← Back

</a>

</div>

</form>

</div>

</body>
</html>