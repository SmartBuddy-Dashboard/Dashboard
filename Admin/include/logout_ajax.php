<?php
require_once('include/config.php');
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['mobile'])) {

    $mobile = $_SESSION['mobile'];

    // OPTIONAL: update DB if needed
    // mysqli_query($conn, "UPDATE users SET status='offline' WHERE mobile='$mobile'");

    session_unset();
    session_destroy();

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'no_session']);
}
?>