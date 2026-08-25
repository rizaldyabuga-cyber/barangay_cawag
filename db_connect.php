<?php
$servername = "sql103.infinityfree.com";
$username = "if0_42534628"; // change if needed
$password = "pRIJy5MzpCdrph";     // change if needed
$dbname = "if0_42534628_barangay_system";

// check connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
