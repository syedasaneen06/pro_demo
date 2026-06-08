<?php
session_start(); // ✅ START SESSION

// 🔒 SESSION CHECK
if(!isset($_SESSION['hod'])){
    header("Location: hod_login.php");
    exit();
}

include 'db.php';

// ✅ ADD EVENT
if(isset($_POST['add_event'])){
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];

    // جلوگیری duplicate event
    $check = mysqli_query($conn, "SELECT * FROM events WHERE event_name='$event_name'");
    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn, "INSERT INTO events (event_name, event_type)
        VALUES ('$event_name', '$event_type')");
    }
}

// ✅ COUNTS
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM refund_request"));
$approved = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM refund_request WHERE status='Approved'"));
$pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM refund_request WHERE status='Pending'"));
$rejected = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM refund_request WHERE status='Rejected'"));
?>

<!DOCTYPE html>
<html>
<head>
<title>HOD Dashboard</title>
</head>
<body>

<h2>HOD Dashboard</h2>

<!-- 👇 SHOW LOGGED IN USER -->
<h4>Welcome <?php echo $_SESSION['hod']; ?></h4>

<a href="export_excel.php">Download Excel</a>

<h3 style="color:blue;">Total: <?php echo $total; ?></h3>
<h3 style="color:orange;">Pending: <?php echo $pending; ?></h3>
<h3 style="color:green;">Approved: <?php echo $approved; ?></h3>
<h3 style="color:red;">Rejected: <?php echo $rejected; ?></h3>

<hr>

<a href="logout.php">Logout</a>

<hr>

<!-- ✅ ADD EVENT -->
<h3>Add Event</h3>

<form method="POST">
<input type="text" name="event_name" placeholder="Event Name" required>

<select name="event_type">
<option value="Workshop">Workshop</option>
<option value="Seminar">Seminar</option>
<option value="Conference">Conference</option>
<option value="Hackathon">Hackathon</option>
</select>

<button type="submit" name="add_event">Add Event</button>
</form>

<hr>

<!-- 🔍 SEARCH -->
<input type="text" id="searchInput" placeholder="Search..."
style="padding:8px; width:250px; margin-bottom:10px;">

<!-- 📊 TABLE -->
<table border="1" cellpadding="10">

<tr>
<th>Sl No</th>
<th>Participants</th>
<th>Subject/Event</th>
<th>Date</th>
<th>Amount</th>
<th>Account Holder</th>
<th>Account No</th>
<th>IFSC</th>
<th>Bank</th>
<th>Branch</th>
<th>Status</th>
<th>Certificate</th>
<th>Payment Proof</th>
<th>Passbook</th>
<th>Action</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM refund_request");
$sl = 1;

while($row = mysqli_fetch_assoc($result)){

$subject_event = trim(
    $row['subject_name'] .
    (!empty($row['event_type']) ? " (" . $row['event_type'] . ")" : "") .
    (!empty($row['event_name']) ? " - " . $row['event_name'] : "")
);
?>

<tr>

<td><?php echo $sl++; ?></td>
<td><?php echo $row['participants']; ?></td>
<td><?php echo $subject_event; ?></td>
<td><?php echo $row['from_date']; ?></td>
<td><?php echo $row['amount']; ?></td>
<td><?php echo $row['account_holder_name']; ?></td>
<td><?php echo $row['account_number']; ?></td>
<td><?php echo $row['ifsc_code']; ?></td>
<td><?php echo $row['bank_name']; ?></td>
<td><?php echo $row['bank_branch']; ?></td>

<td>
<?php
if($row['status']=="Approved"){
    echo "<span style='color:green;'>Approved</span>";
}
elseif($row['status']=="Rejected"){
    echo "<span style='color:red;'>Rejected</span>";
}
else{
    echo "<span style='color:orange;'>Pending</span>";
}
?>
</td>

<td>
<?php if(!empty($row['certificate_file'])){ ?>
<button onclick="openPopup('uploads/<?php echo $row['certificate_file']; ?>')">View</button>
<?php } else { echo "No File"; } ?>
</td>

<td>
<?php if(!empty($row['payment_proof'])){ ?>
<button onclick="openPopup('uploads/<?php echo $row['payment_proof']; ?>')">View</button>
<?php } else { echo "No File"; } ?>
</td>

<td>
<?php if(!empty($row['passbook_file'])){ ?>
<button onclick="openPopup('uploads/<?php echo $row['passbook_file']; ?>')">View</button>
<?php } else { echo "No File"; } ?>
</td>

<td>
<?php if($row['status']=="Pending"){ ?>

<a href="approve.php?id=<?php echo $row['id']; ?>"
style="background:green; color:white; padding:5px 10px; text-decoration:none;">
Approve
</a>

<br><br>

<a href="reject.php?id=<?php echo $row['id']; ?>"
style="background:red; color:white; padding:5px 10px; text-decoration:none;">
Reject
</a>

<?php } else { echo "-"; } ?>
</td>

</tr>

<?php } ?>

</table>

<!-- ✅ POPUP -->
<div id="popup" style="display:none; position:fixed; top:5%; left:10%; width:80%; height:85%; background:white; border:2px solid black; z-index:999;">

<button onclick="closePopup()" style="float:right; background:red; color:white;">X</button>

<iframe id="popupFrame" style="width:100%; height:90%; border:none;"></iframe>

</div>

<script>
function openPopup(file){
    document.getElementById("popup").style.display = "block";
    document.getElementById("popupFrame").src = file;
}

function closePopup(){
    document.getElementById("popup").style.display = "none";
    document.getElementById("popupFrame").src = "";
}

// 🔍 SEARCH
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("table tr");

    rows.forEach((row, index) => {
        if(index === 0) return;
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>