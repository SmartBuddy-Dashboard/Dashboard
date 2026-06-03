<?php
require_once("include/phpMQTT.php");

if (!isset($_GET['machine_id'])) {
    die("Invalid Machine ID");
}

$machine_id = $_GET['machine_id'];



/* MQTT CONFIG */
$server    = "127.0.0.1";
$port      = 1883;
$username  = "Trifrnd";
$password  = "Smart_Trifrnd";
$client_id = "phpMQTT-status-" . uniqid();

$topic = "aarya";   // SAME topic for publish & subscribe

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
$response = null;

/* CALLBACK */
function procmsg($topic, $msg){
    global $response, $machine_id;

    // Ignore own request
    if (strpos($msg, "status?") !== false) {
        return;
    }

    // Accept only this machine reply
    if (strpos($msg, $machine_id) === 0) {
        $response = $msg;
    }
}

/* CONNECT */
if (!$mqtt->connect(true, NULL, $username, $password)) {
    die("MQTT connection failed");
}

/* SUBSCRIBE */
$mqtt->subscribe([
    $topic => ["qos" => 0, "function" => "procmsg"]
], 0);

/* PUBLISH */
$mqtt->publish($topic, $machine_id . ",status?", 0);

$start = time();
while (time() - $start < 5) {
    $mqtt->proc();
    usleep(200000); // 0.2 sec
    if ($response !== null) break;
}

$mqtt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Machine Status</title>
    <script>
        window.onload = function () {
            <?php if ($response): ?>
                alert("✅ Machine Response:\n<?php echo addslashes($response); ?>");
            <?php else: ?>
                alert("❌ No response from machine...");
            <?php endif; ?>
            window.history.back();
        }
    </script>
</head>
<body></body>
</html>
