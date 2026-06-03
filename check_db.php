<?php
include('client/include/config.php');
$res = mysqli_query($conn, 'SELECT * FROM projects');
while($row = mysqli_fetch_assoc($res)) { print_r($row); }
?>
