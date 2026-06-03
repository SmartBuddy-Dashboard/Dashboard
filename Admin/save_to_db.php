<?php
include("include/config.php"); // DB connection

$topic = $_POST['topic'] ?? '';
$machine_id = $_POST['machine_id'] ?? '';

if (!empty($topic) && !empty($machine_id)) {
    $stmt = $conn->prepare("INSERT INTO datatest (topic_name, machine_id, received_time) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $topic, $machine_id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "MQTT log saved",
            "id" => $stmt->insert_id,
            "topic" => $topic,
            "machine_id" => $machine_id,
            "time" => date("Y-m-d H:i:s")
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "DB Insert Failed"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
