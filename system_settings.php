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

if (!isset($_FILES['sql_file'])) {
    die("No file uploaded.");
}

$file = $_FILES['sql_file'];

if ($file['error'] != 0) {
    die("Upload failed.");
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($extension != "sql") {
    die("Only SQL files are allowed.");
}

$sql = file_get_contents($file['tmp_name']);

$queries = explode(";\n", $sql);

$conn->begin_transaction();

try {

    foreach ($queries as $query) {

        $query = trim($query);

        if ($query == "") {
            continue;
        }

        if (strpos($query, "--") === 0) {
            continue;
        }

        $conn->query($query);
    }

    $conn->commit();

    header("Location: restore_database.php?success=1");
    exit();

} catch (Exception $e) {

    $conn->rollback();

    die("Restore failed: " . $e->getMessage());
}
?>