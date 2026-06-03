<?php
// auto_login.php
session_start();

// Force set Client session variables to test the new Dark Mode UI
$_SESSION['mobile'] = '9888888888';
$_SESSION['role']   = 'Client';
$_SESSION['client_name'] = 'Demo Client';

// Redirect to Client dashboard
header("Location: client/list_projectdetails.php");
exit();
?>
