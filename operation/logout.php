<?php
session_start();
require_once('include/config.php');

date_default_timezone_set('Asia/Kolkata');
$ldate=date( 'd-m-Y h:i:s A', time () );

mysqli_query($conn,"UPDATE userlog  SET logout = '$ldate' WHERE username = '".$_SESSION["mobile"]."' ORDER BY id DESC LIMIT 1");


session_unset();
session_destroy();
header("location:../index.php");
exit();

?>