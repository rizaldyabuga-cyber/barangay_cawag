<?php
include "db_connect.php";

$id = $_GET['id'];

$conn->query("DELETE FROM barangay_records WHERE id=$id");

header("Location: barangay_record.php");
exit();
?>
