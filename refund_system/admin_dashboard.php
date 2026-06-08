<?php
session_start();

// 🔒 SESSION CHECK
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// 🔷 GET BRANCH
$branch = $_GET['branch'] ?? '';

// 🔷 QUERY (Approved + 1 month)
$sql = "SELECT * FROM refund_request 
        WHERE status='Approved'
        AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";

if(!empty($branch)){
    $sql .= " AND usn LIKE '%$branch%'";
}

// 🔷 RUN QUERY
$result = mysqli_query($conn, $sql);

// ❌ ERROR CHECK
if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}

// 🔷 COUNT
$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
</head>

<body>

<h2>Admin Dashboard</h2>

<h4>Welcome <?php echo $_SESSION['admin_username']; ?></h4>

<a href="export_excel.php">Download Excel</a>

<h3 style="color:green;">Total Approved: <?php echo $total; ?></h3>

<hr>

<a href="logout.php">Logout</a>

<hr>

<!-- 🔷 BRANCH FILTER -->
<form method="GET">
    <label>Select Branch:</label>
    <select name="branch">
        <option value="">All</option>
        <option value="CS" <?php if($branch=="CS") echo "selected"; ?>>CSE</option>
        <option value="CI" <?php if($branch=="CI") echo "selected"; ?>>AIML</option>
        <option value="EC" <?php if($branch=="EC") echo "selected"; ?>>ECE</option>
    </select>
    <button type="submit">Filter</button>
</form>

<br>

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
</tr>

<?php
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
<td style="color:green;"><?php echo $row['status']; ?></td>

<!-- 📄 CERTIFICATE -->
<td>
<?php if(!empty($row['certificate_file'])){ ?>
<button onclick="openPopup('uploads/<?php echo $row['certificate_file']; ?>')">View</button>
<?php } else { echo "No File"; } ?>
</td>

<!-- 💳 PAYMENT -->
<td>
<?php if(!empty($row['payment_proof'])){ ?>
<button onclick="openPopup('uploads/<?php echo $row['payment_proof']; ?>')">View</button>
<?php } else { echo "No File"; } ?>
</td>

<!-- 📘 PASSBOOK -->
<td>
<?php if(!empty($row['passbook_file'])){ ?>
<button onclick="openPopup('uploads/<?php echo $row['passbook_file']; ?>')">View</button>
<?php } else { echo "No File"; } ?>
</td>

</tr>

<?php } ?>

</table>

<!-- 🔥 POPUP -->
<div id="popup" style="display:none; position:fixed; top:5%; left:10%; width:80%; height:85%; background:white; border:2px solid black; z-index:999;">

<button onclick="closePopup()" 
style="float:right; background:red; color:white; padding:5px;">
X Close
</button>

<iframe id="popupFrame" style="width:100%; height:90%; border:none;"></iframe>

</div>

</body>
</html>

<!-- 🔍 SEARCH SCRIPT -->
<script>
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

<!-- 🔥 POPUP SCRIPT -->
<script>
function openPopup(file){
    document.getElementById("popup").style.display = "block";
    document.getElementById("popupFrame").src = file;
}

function closePopup(){
    document.getElementById("popup").style.display = "none";
    document.getElementById("popupFrame").src = "";
}
</script>