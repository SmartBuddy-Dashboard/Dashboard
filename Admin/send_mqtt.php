<?php
require("include/phpMQTT.php");

$machine_id = $_POST['machine_id'] ?? '';
$status     = $_POST['status'] ?? '';

$server   = "127.0.0.1";
$port     = 1883;
$username = "Trifrnd";
$password = "Smart_Trifrnd";
$client_id = "phpMQTT-publisher";

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
    $topic = 'aarya';

    // ✅ Publish machine_id
    $mqtt->publish($topic, $machine_id, 0);
    $mqtt->close();

    // ✅ Insert into DB (instead of external call)
    include("include/config.php");
    $stmt = $conn->prepare("INSERT INTO datatest (topic_name, machine_id, received_time) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $topic, $machine_id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "MQTT published & log saved",
            "id" => $stmt->insert_id,
            "topic" => $topic,
            "machine_id" => $machine_id,
            "time" => date("Y-m-d H:i:s")
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "MQTT published but DB insert failed"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "❌ MQTT connection failed"]);
}
?>
