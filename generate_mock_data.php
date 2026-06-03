<?php
// generate_mock_data.php
// This script generates thousands of dummy transactions to test the UI load and functionality
// DO NOT RUN THIS IN PRODUCTION!

include('client/include/config.php');

echo "<h2>Mock Data Generator (Load Testing)</h2>";
echo "Starting data generation...<br>";

// 1. Get all active machine IDs
$machine_ids = [];
$q_machines = mysqli_query($conn, "SELECT machine_id FROM machines");
while($row = mysqli_fetch_assoc($q_machines)) {
    $machine_ids[] = $row['machine_id'];
}

if(count($machine_ids) == 0) {
    die("Error: No machines found in the database. Add a machine first.");
}

// 2. Generate Dummy Transactions (trans table)
$num_transactions = 1000; // Generate 1000 transactions for testing
$success_count = 0;

$payment_modes = ['coin', 'upi', 'rfid', 'free'];
$statuses = ['success', 'success', 'success', 'failed']; // 75% success rate
$amounts = [5.00, 10.00, 2.00];

for ($i = 0; $i < $num_transactions; $i++) {
    $m_id = $machine_ids[array_rand($machine_ids)];
    $mode = $payment_modes[array_rand($payment_modes)];
    $status = $statuses[array_rand($statuses)];
    $amount = ($mode === 'free' || $status === 'failed') ? 0.00 : $amounts[array_rand($amounts)];
    
    // Generate random dates within the last 30 days
    $random_days = rand(0, 30);
    $random_hours = rand(0, 23);
    $random_mins = rand(0, 59);
    $date_time = date('Y-m-d H:i:s', strtotime("-$random_days days -$random_hours hours -$random_mins minutes"));

    $stmt = $conn->prepare("INSERT INTO trans (machin_id, trans_amt, payment_mode, status, date_time) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sdsss", $m_id, $amount, $mode, $status, $date_time);
        if($stmt->execute()) {
            $success_count++;
        }
    }
}
echo "✅ Added $success_count dummy transactions.<br>";

// 3. Generate Dummy Live Data (datatest table)
$num_live_data = 500;
$live_count = 0;

for ($i = 0; $i < $num_live_data; $i++) {
    $m_id = $machine_ids[array_rand($machine_ids)];
    
    $water_level = rand(10, 100);
    $chemical_level = rand(5, 100);
    
    $topic = "Water Level: $water_level%, Chemical: $chemical_level%";
    $date_time = date('Y-m-d H:i:s', strtotime("-" . rand(0, 60) . " minutes")); // Recent data

    $stmt = $conn->prepare("INSERT INTO datatest (machine_id, topic_name, received_time) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $m_id, $topic, $date_time);
        if($stmt->execute()) {
            $live_count++;
        }
    }
}
echo "✅ Added $live_count dummy live sensor readings.<br>";

echo "<br><b>Done! You can now test the Dashboard and Reports.</b>";
?>
