<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db_connect.php";
include "utilities/audit_log.php";

$success = "";
$error = "";

if (isset($_POST['save'])) {

    $full_name = trim($_POST['full_name']);
    $gender = trim($_POST['gender']);
    $birth_date = $_POST['birth_date'];
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);

    // Default photo
    $photo = "default.png";

    // Upload Photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        $file_name = $_FILES['photo']['name'];
        $tmp_name = $_FILES['photo']['tmp_name'];

        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($extension, $allowed)) {

            $photo = time() . "_" . preg_replace("/[^A-Za-z0-9._-]/", "_", $file_name);

            $upload_path = "uploads/residents/" . $photo;

            if (!move_uploaded_file($tmp_name, $upload_path)) {
                $photo = "default.png";
            }

        } else {

            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";

        }
    }

    if (empty($error)) {

        $stmt = $conn->prepare("
            INSERT INTO residents
            (
                full_name,
                gender,
                birth_date,
                contact_number,
                address,
                email,
                photo
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssss",
            $full_name,
            $gender,
            $birth_date,
            $contact_number,
            $address,
            $email,
            $photo
        );

        if ($stmt->execute()) {

            $resident_id = $stmt->insert_id;

            logActivity(
                $conn,
                $_SESSION['admin'],
                "ADD",
                "RESIDENTS",
                $resident_id,
                "Added resident: " . $full_name
            );

            $stmt->close();

            header("Location: residents.php?success=Resident added successfully");
            exit();

        } else {

            $error = "Failed to save resident.";

        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Resident</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:url('image/cawag.jpg') no-repeat center center fixed;
    background-size: cover;
    padding:30px;
}

.container{
    max-width:850px;
    margin:auto;
    background:white;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.10);
    overflow:hidden;
}
  

.header{
    background:#3498db;
    color:#fff;
    padding:20px;
}

.header h2{
    font-weight:600;
}

.content{
    padding:30px;
}

.row{
    display:flex;
    gap:20px;
    margin-bottom:20px;
}

.col{
    flex:1;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:500;
    color:#333;
}

input,
select,
textarea{

    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    outline:none;
    font-size:14px;
    transition:.3s;

}

input:focus,
select:focus,
textarea:focus{

    border-color:#3498db;

}

textarea{

    resize:vertical;
    min-height:100px;

}

.photo-preview{

    width:180px;
    height:180px;
    border-radius:10px;
    border:2px dashed #ccc;
    object-fit:cover;
    display:block;
    margin:auto auto 15px;

}

.photo-box{

    text-align:center;
    margin-bottom:25px;

}

.btn-group{

    display:flex;
    justify-content:space-between;
    margin-top:30px;

}

.btn{

    display:inline-block;
    padding:12px 20px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    cursor:pointer;
    font-size:15px;
    transition:.3s;

}

.btn-save{

    background:#27ae60;
    color:#fff;

}

.btn-save:hover{

    background:#219150;

}

.btn-back{

    background:#2c3e50;
    color:#fff;

}

.btn-back:hover{

    background:#1d2b36;

}

.alert{

    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
    font-weight:500;

}

.alert-error{

    background:#fdecea;
    color:#c0392b;

}

@media(max-width:768px){

.row{

    flex-direction:column;

}

.btn-group{

    flex-direction:column;
    gap:10px;

}

.btn{

    width:100%;

}

}

</style>

</head>
<body>

<div class="container">

<div class="header">
    <h2>Add New Resident</h2>
</div>

<div class="content">

<?php if(!empty($error)): ?>

<div class="alert alert-error">
    <?= htmlspecialchars($error); ?>
</div>

<?php endif; ?>
<form method="POST" enctype="multipart/form-data">

    <div class="photo-box">

        <img src="uploads/residents/default.png"
             id="preview"
             class="photo-preview">

        <input
            type="file"
            name="photo"
            accept="image/*"
            onchange="previewImage(event)">

    </div>

    <div class="row">

        <div class="col">

            <label>Full Name</label>

            <input
                type="text"
                name="full_name"
                required>

        </div>

        <div class="col">

            <label>Gender</label>

            <select
                name="gender"
                required>

                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>

            </select>

        </div>

    </div>

    <div class="row">

        <div class="col">

            <label>Birth Date</label>

            <input
                type="date"
                name="birth_date"
                required>

        </div>

        <div class="col">

            <label>Contact Number</label>

            <input
                type="text"
                name="contact_number"
                required>

        </div>

    </div>

    <div class="row">

        <div class="col">

            <label>Address</label>

            <textarea
                name="address"
                required></textarea>

        </div>

    </div>

    <div class="row">

        <div class="col">

            <label>Email</label>

            <input
                type="email"
                name="email">

        </div>

    </div>

    <div class="btn-group">

        <a href="residents.php" class="btn btn-back">
            ← Back
        </a>

        <button
            type="submit"
            name="save"
            class="btn btn-save">

            Save Resident

        </button>

    </div>

</form>

</div>
</div>

<script>

function previewImage(event){

    const reader = new FileReader();

    reader.onload = function(){

        document.getElementById("preview").src = reader.result;

    };

    reader.readAsDataURL(event.target.files[0]);

}

</script>

</body>
</html>