<?php
include "db_connect.php"; // Make sure this connects to your database

$username = "admin"; // default username
$password = "admin123"; // default password

// Create a secure hash of the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert admin into the database
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();
$stmt->close();

echo "Admin user created successfully!";
?>
