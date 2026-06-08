<?php
include 'db.php';
$id = $_GET['id'];
mysqli_query($conn, "UPDATE refund_request SET status='Approved' WHERE id='$id'");
header("Location: hod_dashboard.php");
?>