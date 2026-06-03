<?php
include('client/include/config.php');

echo "<h3>Admin Users</h3>";
$res = mysqli_query($conn, "SELECT mobile, password, name FROM tblusers WHERE role='Admin' LIMIT 1");
if($row = mysqli_fetch_assoc($res)) {
    echo "Mobile: " . $row['mobile'] . "<br>Password: " . $row['password'] . "<br>Name: " . $row['name'] . "<br><br>";
}

echo "<h3>Operation Users</h3>";
$res = mysqli_query($conn, "SELECT mobile, password, name FROM tblusers WHERE role='Operation' LIMIT 1");
if($row = mysqli_fetch_assoc($res)) {
    echo "Mobile: " . $row['mobile'] . "<br>Password: " . $row['password'] . "<br>Name: " . $row['name'] . "<br><br>";
}
?>
