<?php
// operation/save_to_db.php
header('Content-Type: application/json');

$topic = $_POST['topic'] ?? '';
$machine_id = $_POST['machine_id'] ?? '';

if (!empty($topic) && !empty($machine_id)) {
    
    $payload = json_encode([
        'topic_name' => $topic,
        'machine_id' => $machine_id,
        'received_time' => date("Y-m-d H:i:s")
    ]);

    // Industrial File-Based Queue System
    $queueDir = __DIR__ . '/queue';
    if (!is_dir($queueDir)) {
        mkdir($queueDir, 0777, true);
    }
    
    $queueFile = $queueDir . '/iot_telemetry.log';
    
    // FILE_APPEND adds to end. LOCK_EX prevents concurrent write corruption.
    // This is extremely fast and prevents MySQL locking under high load.
    if (file_put_contents($queueFile, $payload . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
        echo json_encode([
            "success" => true,
            "message" => "MQTT log queued to file successfully",
            "queued" => true,
            "topic" => $topic,
            "machine_id" => $machine_id
        ]);
    } else {
        // Fallback to direct DB insert if file system fails (failsafe)
        include("include/config.php"); 
        $stmt = $conn->prepare("INSERT INTO datatest (topic_name, machine_id, received_time) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $topic, $machine_id);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "MQTT log saved directly (File Queue Error)"]);
        } else {
            echo json_encode(["success" => false, "message" => "DB Insert Failed"]);
        }
        if (isset($stmt)) $stmt->close();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
