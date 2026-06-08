<?php
include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM refund_request WHERE id='$id'");
$row = mysqli_fetch_assoc($result);
?>

<h2>Full Details</h2>

<p><b>Participants:</b> <?php echo $row['participants']; ?></p>
<p><b>Event:</b> <?php echo $row['event_name']; ?></p>
<p><b>Amount:</b> <?php echo $row['amount']; ?></p>
<p><b>Status:</b> <?php echo $row['status']; ?></p>