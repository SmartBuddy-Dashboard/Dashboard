<?php
// client/live_tank_stream.php
// Server-Sent Events (SSE) endpoint for real-time updates (Database Polling Method)
session_start();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Turn off output buffering for immediate flush
if (ob_get_level()) ob_end_clean();

// Check authentication
if (!isset($_SESSION['mobile']) || !isset($_SESSION['client_name'])) {
    echo "data: " . json_encode(['error' => 'Unauthorized']) . "\n\n";
    flush();
    exit();
}

// Close session to prevent session blocking (allowing user to navigate to other pages)
$client_name = $_SESSION['client_name'];
session_write_close();

// Use the main configuration file for database connection
include("../include/config.php"); 

// Get machines belonging to this client
$machineIds = [];
$client_name_escaped = mysqli_real_escape_string($conn, $client_name);
$q1 = mysqli_query($conn, "SELECT machine_id FROM machines WHERE client_name = '$client_name_escaped'");
if($q1) {
    while ($row = mysqli_fetch_assoc($q1)) {
        $machineIds[] = "'" . mysqli_real_escape_string($conn, $row['machine_id']) . "'";
    }
}
$machineIdList = count($machineIds) > 0 ? implode(",", $machineIds) : "'NO_MACHINES'";

// Initialize last_id to the current maximum ID in datatest so we only send NEW records
$last_id = 0;
// We only care about max ID of the allowed machines to prevent large gap scans
$initQuery = $conn->query("SELECT MAX(id) as max_id FROM datatest WHERE machine_id IN ($machineIdList)");
if ($initQuery && $row = $initQuery->fetch_assoc()) {
    $last_id = $row['max_id'] ? $row['max_id'] : 0;
}

// Loop indefinitely
while (true) {
    // Reconnect or exit if connection drops
    if (!$conn->ping()) {
        exit();
    }

    if ($machineIdList === "'NO_MACHINES'") {
        // No machines to track, sleep longer
        sleep(5);
        if (connection_aborted()) break;
        continue;
    }

    // Query for new records ONLY for authorized machines
    $query = "SELECT * FROM datatest WHERE id > $last_id AND machine_id IN ($machineIdList) ORDER BY id ASC LIMIT 50";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $last_id = $row['id'];
            
            // SSE format: data: JSON_STRING \n\n
            $payload = json_encode([
                'topic_name' => $row['topic_name'],
                'machine_id' => $row['machine_id'],
                'received_time' => $row['received_time']
            ]);
            
            echo "data: " . $payload . "\n\n";
        }
        flush();
    }
    
    // Sleep for 1.5 seconds to balance responsiveness and server load
    usleep(1500000);
    
    // Exit if the client closes the browser
    if (connection_aborted()) {
        break;
    }
}
?>
