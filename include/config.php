<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_iot');

// Establish database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    // FIX: Log actual error securely, display generic message to user
    error_log("Database Connection Failed: " . mysqli_connect_error());
    die("500 Internal Server Error: Unable to establish database connection.");
}
?>
