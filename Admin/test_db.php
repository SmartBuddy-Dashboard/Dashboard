<?php
error_reporting(E_ALL);
 require_once('include/config.php');
 
ini_set('display_errors', 1);



if (!$conn) {
    die("DB FAILED: " . mysqli_connect_error());
}

echo "DB CONNECTED OK<br>";

$res = mysqli_query($conn, "INSERT INTO machines (machine_id) VALUES ('TEST123')");

if (!$res) {
    die("INSERT FAILED: " . mysqli_error($conn));
}

echo "INSERT OK";
