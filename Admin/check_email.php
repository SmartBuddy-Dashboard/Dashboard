<?php
include('include/config.php');

if(isset($_POST['email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = mysqli_query($conn, "SELECT * FROM clients WHERE contact_email='$email'");

    echo (mysqli_num_rows($query) > 0) ? "exists" : "ok";
}
?>
