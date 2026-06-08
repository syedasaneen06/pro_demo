<?php
include 'db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=refund_data.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "Sl No\tParticipants\tSubject/Event\tDate\tAmount\tAccount Holder\tAccount No\tIFSC\tBank\tBranch\tStatus\n";

$sql = "SELECT * FROM refund_request";
$result = mysqli_query($conn, $sql);

$sl = 1;

while($row = mysqli_fetch_assoc($result)){

$subject_event = trim(
    $row['subject_name'] .
    (!empty($row['event_type']) ? " (" . $row['event_type'] . ")" : "") .
    (!empty($row['event_name']) ? " - " . $row['event_name'] : "")
);

echo $sl++ . "\t" .
$row['participants'] . "\t" .
$subject_event . "\t" .
$row['from_date'] . "\t" .
$row['amount'] . "\t" .
$row['account_holder_name'] . "\t" .
$row['account_number'] . "\t" .
$row['ifsc_code'] . "\t" .
$row['bank_name'] . "\t" .
$row['bank_branch'] . "\t" .
$row['status'] . "\n";

}
?>