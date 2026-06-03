<?php
// force_users.php
include("include/config.php");

echo "<h1>System Repair Script</h1>";

// 1. Fix the password column size so BCRYPT hashes fit (60 chars)
$alterQuery = "ALTER TABLE tblusers MODIFY password VARCHAR(255)";
if (mysqli_query($conn, $alterQuery)) {
    echo "<p style='color:green'>✅ tblusers password column resized to 255 chars.</p>";
} else {
    echo "<p style='color:orange'>⚠️ Could not alter tblusers: " . mysqli_error($conn) . "</p>";
}

$alterQuery2 = "ALTER TABLE clients MODIFY password VARCHAR(255)";
if (mysqli_query($conn, $alterQuery2)) {
    echo "<p style='color:green'>✅ clients password column resized to 255 chars.</p>";
} else {
    echo "<p style='color:orange'>⚠️ Could not alter clients: " . mysqli_error($conn) . "</p>";
}

// 2. Force Insert the Operation User
$opMobile = "9999999999";
$opPass = "123456"; // Plaintext, will be auto-hashed on first login

// Check if exists
$check = mysqli_query($conn, "SELECT id FROM tblusers WHERE mobile='$opMobile'");
if (mysqli_num_rows($check) == 0) {
    $ins = "INSERT INTO tblusers (mobile, password, role, status) VALUES ('$opMobile', '$opPass', 'Operation', 1)";
    if (mysqli_query($conn, $ins)) {
        echo "<p style='color:green'>✅ Operation User (9999999999) injected successfully.</p>";
    } else {
        echo "<p style='color:red'>❌ Failed to inject Operation User: " . mysqli_error($conn) . "</p>";
    }
} else {
    // Force reset password if user already exists
    mysqli_query($conn, "UPDATE tblusers SET password='$opPass', status=1 WHERE mobile='$opMobile'");
    echo "<p style='color:green'>✅ Operation User (9999999999) already existed. Password reset to: $opPass</p>";
}

// 3. Force Insert the Client User
$clMobile = "9888888888";
$clPass = "12345";

$check2 = mysqli_query($conn, "SELECT id FROM clients WHERE contact_mobile='$clMobile'");
if (mysqli_num_rows($check2) == 0) {
    // We omit 'status' since it caused an error in HeidiSQL earlier (it might not exist in the schema)
    $ins2 = "INSERT INTO clients (client_name, contact_mobile, password) VALUES ('Demo Client', '$clMobile', '$clPass')";
    if (mysqli_query($conn, $ins2)) {
        echo "<p style='color:green'>✅ Client User (9888888888) injected successfully.</p>";
    } else {
        echo "<p style='color:red'>❌ Failed to inject Client User: " . mysqli_error($conn) . "</p>";
    }
} else {
    mysqli_query($conn, "UPDATE clients SET password='$clPass' WHERE contact_mobile='$clMobile'");
    echo "<p style='color:green'>✅ Client User (9888888888) already existed. Password reset to: $clPass</p>";
}

// 4. Force Create userlog table
$userlogSql = "CREATE TABLE IF NOT EXISTS `userlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `userip` varchar(50) NOT NULL,
  `loginTime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (mysqli_query($conn, $userlogSql)) {
    echo "<p style='color:green'>✅ userlog table ensured to exist.</p>";
}

echo "<h2><a href='index.php'>👉 Click Here to Login Now</a></h2>";
echo "<p>Use <b>9999999999</b> / <b>123456</b></p>";
?>
