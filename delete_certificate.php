<?php
include "db_connect.php";

$id = $_GET['id'];

$conn->query("DELETE FROM certificates WHERE id=$id");

header("Location: certificates.php");
exit();
?>
