<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}

include "../db_connect.php";

$sql = "TRUNCATE TABLE audit_logs";

if(!$conn->query($sql)){
    die("Error clearing logs: " . $conn->error);
}

header("Location: activity_logs.php");
exit();
?>