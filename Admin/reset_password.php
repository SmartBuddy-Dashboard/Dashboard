<?php
require_once('include/config.php');
echo "<h2>Resetting Passwords...</h2>";
$res = mysqli_query($conn, "SELECT email FROM tbl_users");
$count = 0;
if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        $email = $row['email'];
        $p = password_hash('admin123', PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE tbl_users SET password='$p' WHERE email='$email'");
        echo "Password for <strong>$email</strong> reset to: <strong>admin123</strong><br>";
        $count++;
    }
} else {
    echo "No users found in tbl_users. Creating one...<br>";
    $p = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO tbl_users (name, email, password, role) VALUES ('Admin', 'admin@smartbuddy.com', '$p', '1')");
    echo "Created user:<br>Email: <strong>admin@smartbuddy.com</strong><br>Password: <strong>admin123</strong><br>";
}

echo "<br><br><a href='login.php'>Click here to go back to Login</a>";
?>
