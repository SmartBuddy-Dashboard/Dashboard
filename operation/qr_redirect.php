<?php
include('include/config.php');

if (!isset($_GET['machine_id'])) {
    die("Invalid QR");
}

$machine_id = mysqli_real_escape_string($conn, $_GET['machine_id']);

/* Fetch amount from DB */
$q = mysqli_query($conn, "
    SELECT uses_amt 
    FROM machines 
    WHERE machine_id='$machine_id'
");

$row = mysqli_fetch_assoc($q);

if (!$row) {
    die("Machine not found");
}

$amount = $row['uses_amt'];

/* FINAL PAYMENT LINK (SAME AS YOU WANT) */
$finalUrl = "https://smartbuddy.co.in/smartqr/pay_from_qr.php?"
          . "machine_id=$machine_id&amount=$amount";

          //https://smartbuddy.co.in/smartqr/pay_from_qr.php?machine_id=DEMO122244&amount=5

/* Redirect */
header("Location: $finalUrl");
exit;
?>