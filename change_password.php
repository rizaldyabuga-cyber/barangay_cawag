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

$database = "if0_42534628_barangay_system";

$filename = "barangay_backup_" . date("Y-m-d_H-i-s") . ".sql";

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = "-- Barangay Cawag Management System Backup\n";
$output .= "-- Date: " . date("Y-m-d H:i:s") . "\n\n";
$output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

$tables = [];

$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

foreach ($tables as $table) {

    // Table Structure
    $create = $conn->query("SHOW CREATE TABLE `$table`")->fetch_assoc();

    $output .= "-- ----------------------------\n";
    $output .= "-- Table structure for `$table`\n";
    $output .= "-- ----------------------------\n\n";

    $output .= "DROP TABLE IF EXISTS `$table`;\n";
    $output .= $create['Create Table'] . ";\n\n";

    // Table Data
    $rows = $conn->query("SELECT * FROM `$table`");

    while ($data = $rows->fetch_assoc()) {

        $values = [];

        foreach ($data as $value) {

            if ($value === null) {
                $values[] = "NULL";
            } else {
                $values[] = "'" . $conn->real_escape_string($value) . "'";
            }

        }

        $output .= "INSERT INTO `$table` VALUES(" . implode(",", $values) . ");\n";
    }

    $output .= "\n\n";
}

$output .= "SET FOREIGN_KEY_CHECKS=1;\n";

echo $output;
exit();
?>