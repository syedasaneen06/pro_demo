<?php
session_start();   // ✅ MUST BE FIRST
include 'db.php';

// 🔒 SESSION CHECK
if(!isset($_SESSION['usn'])){
    header("Location: index.php");
    exit();
}

$usn = $_SESSION['usn'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>

<style>
body {
    margin:0;
    font-family: Arial;
    background:#f4f6f9;
}

.header {
    background:#2c3e50;
    color:white;
    padding:15px;
    display:flex;
    justify-content:space-between;
}

.container {
    display:flex;
    justify-content:center;
    margin-top:30px;
}

.form-box {
    background:white;
    padding:25px;
    border-radius:10px;
    width:500px;
}

input, textarea, select {
    width:100%;
    padding:10px;
    margin:8px 0;
}

button {
    width:100%;
    padding:12px;
    background:#3498db;
    color:white;
    border:none;
}

.logout {
    color:white;
    text-decoration:none;
    background:red;
    padding:6px 10px;
    border-radius:5px;
}
</style>

</head>

<body>

<div class="header">
    <h3>Welcome <?php echo $usn; ?></h3>
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="container">
<div class="form-box">

<h2>Refund Request Form</h2>

<form action="submit_form.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="usn" value="<?php echo $usn; ?>">

<label>Participants:</label>
<textarea name="participants" required></textarea>

<label>Select Event:</label>
<select name="event_name" required>
<option value="">-- Select Event --</option>

<?php
$result = mysqli_query($conn, "SELECT DISTINCT event_name, event_type FROM events");

if($result){
    while($row = mysqli_fetch_assoc($result)){
?>
<option value="<?php echo $row['event_name']; ?>">
<?php echo $row['event_name']." (".$row['event_type'].")"; ?>
</option>
<?php
    }
}
?>
</select>

<!-- ✅ DATE FORMAT -->
<label>Date:</label>
<input type="text" name="from_date" placeholder="DD-MM-YYYY" required pattern="\d{2}-\d{2}-\d{4}">

<label>Amount:</label>
<input type="number" name="amount" required>

<label>Account Holder Name:</label>
<input type="text" name="account_holder_name" required>

<label>Account Number:</label>
<input type="text" name="account_number" required>

<label>IFSC Code:</label>
<input type="text" name="ifsc_code" required>

<label>Bank Name:</label>
<input type="text" name="bank_name" required>

<label>Bank Branch:</label>
<input type="text" name="bank_branch" required>

<label>Bank State:</label>
<input type="text" name="bank_state" required>

<!-- ✅ FILE UPLOADS -->
<label>Upload Certificate:</label>
<input type="file" name="certificate_file" required>

<label>Upload Payment Proof:</label>
<input type="file" name="payment_proof" required>

<!-- ✅ UPI FIELD (AS YOU REQUESTED - BELOW PROOF) -->
<label>UPI ID:</label>
<input type="text" name="upi_id" placeholder="example@upi" required>


<label>Upload Passbook:</label>
<input type="file" name="passbook_file" required>


<button type="submit">Submit Request</button>

</form>

</div>
</div>

</body>
</html>