<?php
include('include/config.php');
/* ===============================
   VERIFY BUTTON CLICK
================================ */
if (isset($_POST['update'])) {

    $new_machine_id = mysqli_real_escape_string($conn, $_POST['new_machine_id']);

    /* -------- MQTT SETTINGS -------- */
    $server   = "127.0.0.1";   // eg: 127.0.0.1
    $port     = 1883;
    $username = "Trifrnd";   // if needed
    $password = "Smart_Trifrnd";   // if needed
    $clientId = "phpMQTT-publisher-" . uniqid();

    $topic   = "aarya";   // 🔴 your topic
    $message = $old_machine_id . ",SET_MACHINE_ID:" . $new_machine_id;

    /* -------- MQTT PUBLISH -------- */
    $mqtt = new phpMQTT($server, $port, $clientId);

    if ($mqtt->connect(true, NULL, $username, $password)) {

        $mqtt->publish($topic, $message, 0);
        $mqtt->close();

        echo "<script>alert('MQTT Command Sent Successfully');</script>";

    } else {
        echo "<script>alert('MQTT Connection Failed');</script>";
    }
}
?>