<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'captain') {
    header("Location: login.php");
    exit();
}

session_regenerate_id(true);

/* ADD ADMIN */
if (isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO admins (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    // Log activity
    $action = "Added new admin: $username";
    $log = $conn->prepare("INSERT INTO activity_logs (username, action) VALUES (?, ?)");
    $log->bind_param("ss", $_SESSION['admin'], $action);
    $log->execute();
}

/* DELETE ADMIN */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("SELECT username FROM admins WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $deletedUser = $result['username'];

    $stmt = $conn->prepare("DELETE FROM admins WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $action = "Deleted admin: $deletedUser";
    $log = $conn->prepare("INSERT INTO activity_logs (username, action) VALUES (?, ?)");
    $log->bind_param("ss", $_SESSION['admin'], $action);
    $log->execute();
}

$admins = $conn->query("SELECT id, username, role FROM admins");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Admins</title>
<style>
/* SAME CSS FOR ALL PAGES */
body{margin:0;font-family:Arial;background:#f4f6f9;}
.wrapper{display:flex;}
.sidebar{width:220px;background:#2c3e50;min-height:100vh;color:white;padding-top:20px;}
.sidebar h3{text-align:center;margin-bottom:30px;}
.sidebar a{display:block;color:white;padding:12px 20px;text-decoration:none;}
.sidebar a:hover{background:#34495e;}
.main{flex:1;padding:30px;}
.header{background:white;padding:15px;margin-bottom:20px;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.card{background:white;padding:20px;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,0.1);margin-bottom:20px;}
table{width:100%;border-collapse:collapse;background:white;border-radius:8px;overflow:hidden;}
th{background:#3498db;color:white;padding:12px;text-align:left;}
td{padding:12px;border-bottom:1px solid #ddd;}
tr:hover{background:#f1f1f1;}
input,select{padding:8px;margin:5px 0;width:100%;border-radius:5px;border:1px solid #ccc;}
button{padding:10px 15px;border:none;background:#3498db;color:white;border-radius:5px;cursor:pointer;}
button:hover{background:#2980b9;}
.delete-btn{background:#e74c3c;padding:6px 10px;border-radius:5px;color:white;text-decoration:none;}
.delete-btn:hover{background:#c0392b;}
</style>
</head>
<body>

<div class="wrapper">

<div class="sidebar">
<h3>Captain Panel</h3>
<a href="captain_dashboard.php">Dashboard</a>
<a href="captain_activity_log.php">Activity Logs</a>
<a href="logout.php">Logout</a>
</div>

<div class="main">

<div class="header">
Welcome, <?= htmlspecialchars($_SESSION['admin']) ?>
</div>

<div class="card">
<h2>Add New Admin</h2>
<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<select name="role">
<option value="staff">Staff</option>
<option value="admin">Admin</option>
<option value="captain">Captain</option>
</select>
<button name="add_admin">Add Admin</button>
</form>
</div>

<div class="card">
<h2>Admin List</h2>
<table>
<tr>
<th>Username</th>
<th>Role</th>
<th>Action</th>
</tr>

<?php while($row = $admins->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['username']) ?></td>
<td><?= htmlspecialchars($row['role']) ?></td>
<td>
<?php if ($row['username'] !== $_SESSION['admin']): ?>
<a class="delete-btn" href="?delete=<?= $row['id'] ?>">Delete</a>
<?php else: ?>
(You)
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>
</div>
</body>
</html>