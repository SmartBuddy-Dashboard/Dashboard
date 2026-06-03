<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'razorpay/Razorpay.php';
use Razorpay\Api\Api;

// Razorpay test keys

$keyId = "rzp_test_RB30ml3UlLynEq";
$keySecret = "9jgUTLJZqWiz2E1AiXQVw1K9";


header("Content-Type: application/json");

// Get values from POST
$machine_id = $_POST['machine_id'] ?? '';
$amount     = $_POST['amount'] ?? 0;

if (empty($machine_id) || empty($amount)) {
    http_response_code(400);
    echo json_encode(["error" => "Machine ID or amount not provided"]);
    exit;
}

$paisaAmount = $amount * 100; // Convert to paisa

try {
    $api = new Api($keyId, $keySecret);
    $order = $api->order->create([
        'receipt' => 'receipt_' . $machine_id,
        'amount' => $paisaAmount,
        'currency' => 'INR',
        'payment_capture' => 1
    ]);

    echo json_encode([
        "key" => $keyId,
        "amount" => $paisaAmount,
        "order_id" => $order['id'],
        "machine_id" => $machine_id
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to create Razorpay order: " . $e->getMessage()]);
}
?>
