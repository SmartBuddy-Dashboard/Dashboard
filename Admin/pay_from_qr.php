<?php
include('include/config.php');
require("include/phpMQTT.php");

// ✅ Validate required parameters
if (!isset($_GET['machine_id']) || !isset($_GET['amount'])) {
    die("Invalid QR data.");
}

$machine_id = $_GET['machine_id'];
$amount     = $_GET['amount'];

// ✅ Fetch current machine status from DB
$query = "SELECT status FROM machines WHERE machine_id = '$machine_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$status = $row ? $row['status'] : 'unknown';

// ✅ MQTT Broker Configuration
$server   = "127.0.0.1";       // Local Mosquitto broker
$port     = 1883;              // TCP port for PHP publisher
$username = "Trifrnd";
$password = "Smart_Trifrnd";
$client_id = "phpMQTT-" . uniqid();

// ✅ Initialize MQTT Client
$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

// ✅ Publish "status?" message
if ($mqtt->connect(true, NULL, $username, $password)) {
    $topic = "aarya";
    $message = "$machine_id,status?";

    $mqtt->publish($topic, $message, 0);
    $mqtt->close();
} else {
    die("❌ Failed to connect to MQTT broker.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Machine Payment</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<style>
body {
    font-family: Arial, sans-serif;
    text-align: center;
    padding-top: 50px;
}
#statusBox {
    margin-top: 30px;
    font-size: 18px;
    color: gray;
}
#loader {
    margin-top: 20px;
    width: 60px;
    height: 60px;
    border: 6px solid #ccc;
    border-top-color: #3399cc;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
</head>
<body>
    <h2>🧠 Waiting for Machine Status...</h2>
    <p>Machine ID: <b><?php echo htmlspecialchars($machine_id); ?></b></p>
    <p>Amount: ₹<b><?php echo htmlspecialchars($amount); ?></b></p>

    <div id="statusBox">Connecting to MQTT...</div>
    <center>
    <div id="loader"></div></center>

<script>
$(document).ready(function () {
    const machineId = "<?php echo $machine_id; ?>";
    const amount = "<?php echo $amount; ?>";

    // ✅ Connect to MQTT Broker over Secure WebSocket
    const client = mqtt.connect("wss://smartbuddy.co.in:9001", {
        username: "Trifrnd",     // comment out if allow_anonymous true
        password: "Smart_Trifrnd",
        clientId: "webClient-" + Math.random().toString(16).substr(2, 8),
        clean: true,
        reconnectPeriod: 2000,
        connectTimeout: 8000,
        keepalive: 60,
        protocolVersion: 4
    });

    client.on("connect", function () {
        console.log("✅ MQTT Connected (WebSocket)");
        $("#statusBox").text("✅ Connected! Waiting for machine status...").css("color", "green");
        client.subscribe("aarya");
    });

    client.on("message", function (topic, message) {
        const msg = message.toString().trim();
        console.log("📩 Message Received:", msg);

        const [recvId, recvStatus] = msg.split(",");
        if (recvId !== machineId) return;

        const status = recvStatus.toLowerCase();

        if (status === "ready") {
            $("#statusBox").css("color", "green").text("✅ Machine Ready! Opening Payment...");
            $("#loader").hide();
            openPaymentGateway();
        } 
        
    });

    client.on("error", function (err) {
        console.error("❌ MQTT Error:", err);
        $("#statusBox").css("color", "red").text("❌ MQTT Connection Failed!");
        $("#loader").hide();
    });

    // ✅ Razorpay Payment Gateway
    function openPaymentGateway() {
        $.post("payorder.php", { machine_id: machineId, amount: amount }, function (orderData) {
            if (orderData.error) {
                alert(orderData.error);
                return;
            }

            let transactionSaved = false;
            const options = {
                key: orderData.key,
                amount: orderData.amount,
                currency: "INR",
                name: "Machine Payment",
                description: "Payment for Machine " + machineId,
                order_id: orderData.order_id,
                prefill: {
                    name: "Test User",
                    email: "test@example.com",
                    contact: "9999999999"
                },
                handler: function (response) {
                    if (transactionSaved) return;
                    transactionSaved = true;

                    // ✅ Create overlay dynamically
                $("body").append(`
                    <div id="paymentOverlay" 
                         style="position:fixed; top:0; left:0; width:100%; height:100%; 
                                background:rgba(0,0,0,0.7); display:flex; 
                                justify-content:center; align-items:center; z-index:9999;">
                        <div id="overlayContent" 
                             style="background:#fff; padding:30px; border-radius:15px; text-align:center;">
                            <img src="loading.gif" alt="Please wait" 
                                 style="width:80px; height:80px; margin-bottom:15px;" />
                            <h2 style="margin:0; color:green;">✅ Payment Successful!</h2>
                            <p style="margin:10px 0; font-size:16px;">Please wait while opening the Door...</p>
                        </div>
                    </div>
                `);

                    // ✅ Save transaction
                    $.post("save_transaction.php", {
                        machine_id: machineId,
                        amount: amount,
                        pay_id: response.razorpay_payment_id,
                        order_id: orderData.order_id,
                        mobile: options.prefill.contact,
                        status: "success"
                    });

                    // ✅ Send MQTT Start Command
                    $.post("send_mqtt.php", {
                        machine_id: machineId,
                        status: "start"
                    });

setTimeout(() => {
    $("#overlayContent").html("<h2 style='color:blue;'>🚪 Door Open Successfully ✅</h2>");
    window.location.href = "thankyou.php";
}, 5000);
                },
                theme: { color: "#3399cc" }
            };

            const rzp1 = new Razorpay(options);

            rzp1.on("payment.failed", function (response) {
                if (transactionSaved) return;
                transactionSaved = true;

                alert("❌ Payment Failed!\n" + response.error.description);
                $.post("save_transaction.php", {
                    machine_id: machineId,
                    amount: amount,
                    pay_id: response.error.metadata.payment_id,
                    order_id: orderData.order_id,
                    mobile: options.prefill.contact,
                    status: "failed"
                });
            });

            rzp1.open();
        }, "json");
    }
});
</script>
</body>
</html>
