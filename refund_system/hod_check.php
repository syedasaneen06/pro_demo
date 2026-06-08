<?php
session_start(); // ✅ MUST

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if($username == "hod" && $password == "123"){

    $_SESSION['hod'] = $username; // ✅ STORE SESSION

    header("Location: hod_dashboard.php");
    exit();

} else {

    echo "<script>alert('Invalid Login'); window.location='hod_login.php';</script>";

}
?>