<?php
include("include/config.php");

$machine_id = $_POST['machine_id'];

$query = mysqli_query($conn, "SELECT machine_id FROM machines WHERE machine_id = '$machine_id'");

if(mysqli_num_rows($query) > 0){
    echo "exists";
} else {
    echo "available";
}
?>
