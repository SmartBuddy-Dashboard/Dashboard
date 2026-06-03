<?php
// operation/queue_worker.php
// Run this via CLI: php queue_worker.php
set_time_limit(0);

// Database connection config
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'smart_iot';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$queueDir = __DIR__ . '/queue';
$queueFile = $queueDir . '/iot_telemetry.log';

echo "Industrial File Queue Worker Started. Waiting for IoT data...\n";

while (true) {
    if (file_exists($queueFile) && filesize($queueFile) > 0) {
        // Read file and clear it atomically
        $fp = fopen($queueFile, "c+");
        if ($fp && flock($fp, LOCK_EX)) {
            $contents = stream_get_contents($fp);
            ftruncate($fp, 0); // Clear the file after reading
            flock($fp, LOCK_UN);
            fclose($fp);
            
            $lines = explode(PHP_EOL, trim($contents));
            $batch = [];
            foreach ($lines as $line) {
                if (empty($line)) continue;
                $data = json_decode($line, true);
                if ($data) $batch[] = $data;
            }
            
            processBatch($conn, $batch);
            
            // FIX: Memory Leak Prevention
            // Free memory explicitly in daemon loop
            unset($contents, $lines, $batch);
            gc_collect_cycles();
        } else {
            if ($fp) fclose($fp);
        }
    }
    
    // Sleep for 1 second to prevent CPU burn
    sleep(1);
}

function processBatch($conn, $batch) {
    if (empty($batch)) return;

    $query = "INSERT INTO datatest (topic_name, machine_id, received_time) VALUES ";
    $values = [];
    $types = "";
    $bindParams = [];

    foreach ($batch as $data) {
        $values[] = "(?, ?, ?)";
        $types .= "sss";
        $bindParams[] = $data['topic_name'];
        $bindParams[] = $data['machine_id'];
        $bindParams[] = $data['received_time'];
    }

    $query .= implode(', ', $values);
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        // Dynamic binding
        $stmt->bind_param($types, ...$bindParams);
        if ($stmt->execute()) {
            echo "[" . date("Y-m-d H:i:s") . "] Inserted " . count($batch) . " records from file queue.\n";
        } else {
            echo "[" . date("Y-m-d H:i:s") . "] Failed to insert batch: " . $stmt->error . "\n";
        }
        $stmt->close();
    } else {
        echo "[" . date("Y-m-d H:i:s") . "] Prepare failed: " . $conn->error . "\n";
    }
}
?>
