<?php
include('include/config.php'); // Make sure you include DB connection

if (!isset($_GET['machine_id']) || !isset($_GET['amount'])) {
    die("Invalid QR data.");
}
$machine_id = $_GET['machine_id'];
$amount     = $_GET['amount'];

// Fetch machine status from DB
$query = "SELECT status FROM machines WHERE machine_id = '$machine_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$status = '';

if ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status'];
} else {
    die("Machine not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<script>
$(document).ready(function () {
    var machineId = "<?php echo $machine_id; ?>";
    var amount = "<?php echo $amount; ?>";
    var status = "<?php echo $status; ?>";

    if (status === "Busy") {
        alert("❌ Machine is busy, please wait for some time.");
        window.location.href = "list_machinedetails.php";
        return;
    } else if (status === "Maintenance") {
        alert("⚠️ Machine is under maintenance.");
        window.location.href = "list_machinedetails.php";
        return;
    } else if (status === "Ready") {
        $.post("payorder.php", { machine_id: machineId, amount: amount }, function (orderData) {
            if (orderData.error) {
                alert(orderData.error);
                return;
            }

            // ✅ Prevent duplicate inserts
            var transactionSaved = false;

            var options = {
                "key": orderData.key,
                "amount": orderData.amount,
                "currency": "INR",
                "name": "Machine Payment",
                "description": "Payment for Machine " + machineId,
                "order_id": orderData.order_id,
                "prefill": {
                    "name": "Test User",
                    "email": "test@example.com",
                    "contact": "9999999999"
                },
                "handler": function (response) {
                    if (transactionSaved) return; // ✅ stop duplicate DB insert
                    transactionSaved = true;

                    // ✅ Create overlay dynamically
                    var overlay = `
                        <div id="paymentOverlay" 
                             style="position:fixed; top:0; left:0; width:100%; height:100%; 
                                    background:rgba(0,0,0,0.7); display:flex; 
                                    justify-content:center; align-items:center; z-index:9999;">
                            <div style="background:#fff; padding:30px; border-radius:15px; text-align:center;">
                                <img src="loading.gif" alt="Please wait" style="width:80px; height:80px; margin-bottom:15px;" />
                                <h2 style="margin:0; color:green;">✅ Payment Successful!</h2>
                                <p style="margin:10px 0; font-size:16px;">Please wait while open the Door...</p>
                                
                            </div>
                        </div>
                    `;
                    $("body").append(overlay);

                    // ✅ Save transaction immediately
                    $.post("save_transaction.php", {
                        machine_id: machineId,
                        amount: amount,
                        pay_id: response.razorpay_payment_id,
                        order_id: orderData.order_id,
                        mobile: options.prefill.contact,
                        status: "success"
                    }, function (res) {
                        console.log("Transaction Saved:", res);

                        // ✅ Send MQTT immediately
                        $.post("send_mqtt.php", {
                            machine_id: machineId,
                            status: "start"
                        }, function (mqttRes) {
                            console.log("MQTT:", mqttRes);
                        }, "json");
                    });

                    // ✅ Keep overlay for 20 seconds, then remove and redirect
                    setTimeout(function () {
                        $("#paymentOverlay").remove();
                       
                    }, 20000);
                },
                "theme": { "color": "#3399cc" }
            };

            var rzp1 = new Razorpay(options);

            rzp1.on("payment.failed", function (response) {
                if (transactionSaved) return; // ✅ stop duplicate DB insert
                transactionSaved = true;

                alert(
                    "❌ Payment Failed!\n" +
                    "Machine ID: " + machineId + "\n" +
                    "Amount: ₹" + amount + "\n" +
                    "Error: " + response.error.description
                );

                $.post("save_transaction.php", {
                    machine_id: machineId,
                    amount: amount,
                    pay_id: response.error.metadata.payment_id,
                    order_id: orderData.order_id,
                    mobile: options.prefill.contact,
                    status: "failed"
                }, function (res) {
                    console.log("Transaction Failed Saved:", res);
                });
            });

            // ✅ Open Razorpay window
            rzp1.open();
        }, "json");
    }
});



</script>
</body>
</html>
