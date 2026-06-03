<?php
// api/iot_post.php
// This API Endpoint receives JSON data directly from the SIMCom A7672S Hardware
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Use existing database config
require_once("../client/include/config.php");

// 1. Get raw POST data sent by the SIM Module
$raw_data = file_get_contents("php://input");

if (empty($raw_data)) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit();
}

// 2. Decode the JSON data
$data = json_decode($raw_data, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format"]);
    exit();
}

// 3. Extract variables from JSON
$machine_id = isset($data['machine_id']) ? mysqli_real_escape_string($conn, $data['machine_id']) : null;
$type = isset($data['type']) ? mysqli_real_escape_string($conn, $data['type']) : null; // 'transaction' or 'live_update'

if (!$machine_id || !$type) {
    echo json_encode(["status" => "error", "message" => "Missing machine_id or type"]);
    exit();
}

// 4. Process the data based on type
if ($type === 'transaction') {
    // Hardware is sending a Coin / UPI payment completion
    $amount = isset($data['amount']) ? (float)$data['amount'] : 0.00;
    $payment_mode = isset($data['payment_mode']) ? mysqli_real_escape_string($conn, $data['payment_mode']) : 'coin'; // coin, upi, rfid, free
    $status = isset($data['status']) ? mysqli_real_escape_string($conn, $data['status']) : 'success';
    
    $query = "INSERT INTO trans (machin_id, amount, payment_mode, status, date_time) 
              VALUES ('$machine_id', '$amount', '$payment_mode', '$status', NOW())";
              
    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Transaction saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
    }

} elseif ($type === 'live_update') {
    // Hardware is sending Live Water Level / Door Status updates
    // For now we just insert into datatest so the live_tank_stream.php can pick it up
    $water_level = isset($data['water_level']) ? (int)$data['water_level'] : 0;
    // We format it as a topic string so it works with the existing dashboard layout
    $topic_name = "Water Level: " . $water_level . "%"; 
    
    $query = "INSERT INTO datatest (machine_id, topic_name, received_time) 
              VALUES ('$machine_id', '$topic_name', NOW())";
              
    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Live data updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . mysqli_error($conn)]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Unknown type"]);
}
?>
