<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "db_connect.php";
include "utilities/audit_log.php";

// Log the logout BEFORE destroying the session
if (isset($_SESSION['admin'])) {

    logActivity(
        $conn,
        $_SESSION['admin'],
        "LOGOUT",
        "AUTH",
        null,
        "User logged out of the system."
    );
}

session_destroy();

header("Location: login.php");
exit();
?>