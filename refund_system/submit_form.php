<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Form Data
    $usn = $_POST['usn'] ?? '';
    $participants = $_POST['participants'] ?? '';
    $subject_name = $_POST['subject_name'] ?? '';
    $from_date = $_POST['from_date'] ?? '';
    $to_date = $_POST['to_date'] ?? '';
    $event_type = $_POST['event_type'] ?? '';
    $event_name = $_POST['event_name'] ?? '';
    $place = $_POST['place'] ?? '';
    $amount = $_POST['amount'] ?? '';

    $account_holder_name = $_POST['account_holder_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';
    $ifsc_code = $_POST['ifsc_code'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $bank_branch = $_POST['bank_branch'] ?? '';
    // ✅ VALIDATION START

// Account Number (9–18 digits only)
if(!preg_match("/^[0-9]{9,18}$/", $account_number)){
    echo "<script>alert('Invalid Account Number (9-18 digits only)'); window.history.back();</script>";
    exit();
}

// IFSC Code (Example: SBIN0001234)
$ifsc_code = strtoupper($ifsc_code);

if(!preg_match("/^[A-Z]{4}0[0-9]{6}$/", $ifsc_code)){
    echo "<script>alert('Invalid IFSC Code (Example: SBIN0001234)'); window.history.back();</script>";
    exit();
}

// ✅ VALIDATION END

    // FILES
    $certificate = $_FILES['certificate_file']['name'] ?? '';
    $proof = $_FILES['payment_proof']['name'] ?? '';
    $passbook = $_FILES['passbook_file']['name'] ?? ''; // ✅ ADDED

    // Create uploads folder
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Upload files
    if (!empty($certificate)) {
        move_uploaded_file($_FILES['certificate_file']['tmp_name'], "uploads/" . $certificate);
    }

    if (!empty($proof)) {
        move_uploaded_file($_FILES['payment_proof']['tmp_name'], "uploads/" . $proof);
    }

    if (!empty($passbook)) {   // ✅ ADDED
        move_uploaded_file($_FILES['passbook_file']['tmp_name'], "uploads/" . $passbook);
    }

    // SQL (UPDATED)
    $sql = "INSERT INTO refund_request
            (usn, participants, subject_name, from_date, to_date, event_type, event_name, place, amount,
            account_holder_name, account_number, ifsc_code, bank_name, bank_branch,
            certificate_file, payment_proof, passbook_file, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        // ✅ 17 variables now (added passbook)
        mysqli_stmt_bind_param($stmt, "sssssssssssssssss",
            $usn, $participants, $subject_name, $from_date, $to_date,
            $event_type, $event_name, $place, $amount,
            $account_holder_name, $account_number, $ifsc_code,
            $bank_name, $bank_branch,
            $certificate, $proof, $passbook
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "Refund Request Submitted Successfully";
        } else {
            echo "Execution Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Preparation Error: " . mysqli_error($conn);
    }

} else {
    echo "Direct access not allowed.";
}

mysqli_close($conn);
?>