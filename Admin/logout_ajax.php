<?php
// logout_ajax.php
require_once('include/config.php'); // your DB connection
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    // Update database to mark user as logged out
    $update = mysqli_query($conn, "UPDATE tblusers SET is_logged_in = 0 WHERE id = '$uid'");

    // Destroy the session
    session_unset();
    session_destroy();

    echo json_encode(['status' => 'success']);
    exit();
} else {
    // No active session
    echo json_encode(['status' => 'no_session']);
    exit();
}
?>
