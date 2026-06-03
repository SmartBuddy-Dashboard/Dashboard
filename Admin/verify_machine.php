<?php
require("include/phpMQTT.php");
include('include/config.php');

/* ===============================
   VERIFY BUTTON CLICK
================================ */
if (isset($_POST['update'])) {

    $id = intval($_POST['id']);

    // 🔹 Fetch OLD machine ID from DB
    $res = mysqli_query($conn, "SELECT machine_id FROM machines WHERE id='$id'");
    if (!$row = mysqli_fetch_assoc($res)) {
        die("Machine not found");
    }

    $old_machine_id = $row['machine_id'];

    // 🔹 New Machine ID
    $new_machine_id = mysqli_real_escape_string($conn, $_POST['new_machine_id']);

    /* -------- MQTT SETTINGS -------- */
    $server   = "127.0.0.1";
    $port     = 1883;
    $username = "Trifrnd";
    $password = "Smart_Trifrnd";
    $clientId = "phpMQTT-publisher-" . uniqid();

    $topic = "aarya";

    // REQUIRED FORMAT
    $message = $old_machine_id . ",SET_MACHINE_ID:" . $new_machine_id;

    /* -------- MQTT PUBLISH -------- */
    $mqtt = new Bluerhinos\phpMQTT($server, $port, $clientId);

    if ($mqtt->connect(true, NULL, $username, $password)) {
        $mqtt->publish($topic, $message, 0);
        $mqtt->close();

        // 🔹 Redirect to mqtt_updatemachine.php page to wait for confirmation
        header("Location: mqtt_updatemachine.php");
        exit();

    } else {
        echo "<script>alert('MQTT Connection Failed');</script>";
    }
}
?>
