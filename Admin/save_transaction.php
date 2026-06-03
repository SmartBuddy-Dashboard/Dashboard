<?php
include('include/config.php'); // DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $machine_id = mysqli_real_escape_string($conn, $_POST['machine_id']);
    $amount     = mysqli_real_escape_string($conn, $_POST['amount']);
    $pay_id     = mysqli_real_escape_string($conn, $_POST['pay_id']);
    $order_id   = mysqli_real_escape_string($conn, $_POST['order_id']);
    $mobile     = mysqli_real_escape_string($conn, $_POST['mobile']);
    $status     = mysqli_real_escape_string($conn, $_POST['status']); // success/failed

    
    $query = "INSERT INTO trans (machin_id, trans_amt, pay_id, trans_id, mobile, status, trans_mode, date_time) 
    VALUES ('$machine_id', '$amount', '$pay_id', '$order_id', '$mobile', '$status', 'upi', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "✅ Transaction saved successfully!";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>

