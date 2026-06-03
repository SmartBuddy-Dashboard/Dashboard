<?php
include('include/config.php');

if (isset($_POST['update'])) {

    $id  = intval($_POST['id']);

    // INPUT VALUES
    $machine_id        = mysqli_real_escape_string($conn, $_POST['machine_id']);
    $client_name       = mysqli_real_escape_string($conn, $_POST['client_name']);
    $state             = mysqli_real_escape_string($conn, $_POST['state']);
    $district          = mysqli_real_escape_string($conn, $_POST['district']);
    $city              = mysqli_real_escape_string($conn, $_POST['city']);
    $address           = mysqli_real_escape_string($conn, $_POST['address']);
    $inst_address      = mysqli_real_escape_string($conn, $_POST['inst_address']);
    $uses_amt          = mysqli_real_escape_string($conn, $_POST['uses_amt']);  
    $status            = mysqli_real_escape_string($conn, $_POST['status']);
    $project_name      = mysqli_real_escape_string($conn, $_POST['project_name']);
    $po_date           = mysqli_real_escape_string($conn, $_POST['po_date']);
   $installation_date = !empty($_POST['installation_date']) 
    ? "'" . mysqli_real_escape_string($conn, $_POST['installation_date']) . "'" 
    : "NULL";

$dispatch_date = !empty($_POST['dispatch_date']) 
    ? "'" . mysqli_real_escape_string($conn, $_POST['dispatch_date']) . "'" 
    : "NULL";

    $wall_clean = mysqli_real_escape_string($conn, $_POST['wall_clean']);

    $seats      = !empty($_POST['seats']) ? mysqli_real_escape_string($conn, $_POST['seats']) : "NULL"; 
    $flush_time = mysqli_real_escape_string($conn, $_POST['flush_time']);
    $floor_time = mysqli_real_escape_string($conn, $_POST['floor_time']);
    $wall_time  = !empty($_POST['wall_time']) ? mysqli_real_escape_string($conn, $_POST['wall_time']) : "NULL";


    // -----------------------------------------
    // PAYMENT METHODS Yes/No
    // -----------------------------------------
    $Button_check          = isset($_POST['Button']);
    $coin_check          = isset($_POST['coin']);
    $upi_check           = isset($_POST['upi']);
    $smart_card_check    = isset($_POST['smart_card']);
    $digital_token_check = isset($_POST['digital_token']);

    $Button          = $Button_check ? 'Yes' : 'No';
    $coin          = $coin_check ? 'Yes' : 'No';
    $upi           = $upi_check ? 'Yes' : 'No';
    $smart_card    = $smart_card_check ? 'Yes' : 'No';
    $digital_token = $digital_token_check ? 'Yes' : 'No';


    // -----------------------------------------
    // MODE LOGIC (First_Second_Third)
    // -----------------------------------------

    $modes = [];

    // Priority based mode selection
    if ($coin_check)          $modes[] = "Coin";
    if ($upi_check)           $modes[] = "UPI";
    if ($Button_check)          $modes[] = "Button";
    if ($smart_card_check)    $modes[] = "SmartCard";
    if ($digital_token_check) $modes[] = "DigitalToken";

    // Take only first 3
    $selected = array_slice($modes, 0, 3);

    // If nothing selected → Default Free
    if (empty($selected)) {
        $mode = "Button";
    } else {
        $mode = implode("_", $selected);
    }


   
 $oldValuesQuery = mysqli_query($conn, "
    SELECT machine_id, status, uses_amt, wall_clean, seats,
           flush_time, floor_time, wall_time,
           free, coin, upi, smart_card, digital_token
    FROM machines WHERE id='$id'
");
    $old = mysqli_fetch_assoc($oldValuesQuery);

    // UPDATE
$update = "UPDATE machines SET
    machine_id        = '$machine_id',
    client_name       = '$client_name',
    state             = '$state',
    district          = '$district',
    city              = '$city',
    address           = '$address',
    inst_address      = '$inst_address',
    uses_amt          = '$uses_amt',
    status            = '$status',
    project_name      = '$project_name',
    po_date           = '$po_date',
    installation_date = $installation_date,
    dispatch_date     = $dispatch_date,
    wall_clean        = '$wall_clean',
    seats             = $seats,
    flush_time        = '$flush_time',
    floor_time        = '$floor_time',
    wall_time         = $wall_time,
    free              = '$Button',
    coin              = '$coin',
    upi               = '$upi',
    smart_card        = '$smart_card',
    digital_token     = '$digital_token'
WHERE id = '$id'";


    if (mysqli_query($conn, $update)) {

        // LOG STATUS
        if ($old['status'] !== $status) {

            mysqli_query($conn, "
                UPDATE machine_logs 
                SET end_time = NOW() 
                WHERE machine_id = '$machine_id' 
                AND end_time IS NULL
            ");

            mysqli_query($conn, "
                INSERT INTO machine_logs (machine_id, status, start_time)
                VALUES ('$machine_id', '$status', NOW())
            ");
        }

        // MQTT TRIGGER IF CHANGED
        $publishMessage = "";

        $isChanged = (
    $old['machine_id'] != $machine_id ||
$old['status']     != $status     ||
$old['uses_amt']   != $uses_amt   ||
$old['wall_clean'] != $wall_clean ||
$old['seats']      != $seats      ||
$old['flush_time'] != $flush_time ||
$old['floor_time'] != $floor_time ||
$old['wall_time']     != $wall_time  ||
    $old['free']          != $Button     ||
    $old['coin']          != $coin       ||
    $old['upi']           != $upi        ||
    $old['smart_card']    != $smart_card ||
    $old['digital_token'] != $digital_token
        );

        if ($isChanged) {

            // MQTT MESSAGE FORMAT
            $publishMessage = implode(",", [
                $machine_id,
                "SET_PARAMETERS",
                $status,
                $mode,
                $uses_amt,
                $wall_clean,
                $seats,
                $flush_time,
                $floor_time,
                $wall_time
            ]);

            // PUBLISH
            require("include/phpMQTT.php");

            $mqtt = new Bluerhinos\phpMQTT("127.0.0.1", 1883, "pub-" . uniqid());

            if ($mqtt->connect(true, NULL, "Trifrnd", "Smart_Trifrnd")) {
                $mqtt->publish("aarya", $publishMessage, 0);
                $mqtt->close();
            }
        }

         echo "<script>
                alert('Machine updated successfully.');
                window.location.href='list_machinedetails.php';
              </script>";
    } else {
        echo "<script>alert('Error updating'); history.back();</script>";
    }

} else {
    echo "<script>alert('Invalid request'); location.href='list_machinedetails.php';</script>";
}
?>
