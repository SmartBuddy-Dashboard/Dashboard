<?php
include('client/include/config.php');
$res = mysqli_query($conn, 'SELECT contact_mobile, password, client_name FROM clients LIMIT 1');
if($row = mysqli_fetch_assoc($res)) {
    echo "Mobile: " . $row['contact_mobile'] . "<br>Password: " . $row['password'] . "<br>Name: " . $row['client_name'];
} else {
    echo 'No clients found in the database.';
}
?>
