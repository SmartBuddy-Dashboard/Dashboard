<?php
require("include/phpMQTT.php");

$server   = "localhost"; 
$port     = 1883;
$username = "Trifrnd";
$password = "Smart_Trifrnd";
$client_id = "phpMQTT-Subscriber-" . uniqid();

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if(!$mqtt->connect(true, NULL, $username, $password)) {
    exit("❌ Failed to connect to MQTT broker\n");
}

// ✅ Subscribe ONCE
$topics['machines/#'] = array("qos" => 0, "function" => "procMsg");
$mqtt->subscribe($topics, 0);

// ✅ Main loop: only process incoming messages
while($mqtt->proc()){
    // keep listening
}

$mqtt->close();

function procMsg($topic, $msg){
    echo "📩 Received on [$topic]: $msg\n";
}
?>
