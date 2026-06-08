<?php
session_start();
include 'db.php';

$usn = trim($_POST['usn'] ?? '');
$password = trim($_POST['password'] ?? '');

if($usn != "" && $password != ""){

    $sql = "SELECT * FROM student WHERE usn='$usn' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0){

        $_SESSION['usn'] = $usn;

        header("Location: dashboard.php");
        exit();

    } else {
        echo "<script>alert('Invalid Login'); window.location='index.php';</script>";
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>