<?php

function logActivity($conn, $username, $action, $module, $record_id = null, $description = "")
{
    $stmt = $conn->prepare("
        INSERT INTO audit_logs
        (username, action, module, record_id, description)
        VALUES (?, ?, ?, ?, ?)
    ");

    if(!$stmt){
        die("Prepare failed: ".$conn->error);
    }


    $stmt->bind_param(
        "sssis",
        $username,
        $action,
        $module,
        $record_id,
        $description
    );


    if(!$stmt->execute()){
        die("Log failed: ".$stmt->error);
    }


    $stmt->close();
}

?>