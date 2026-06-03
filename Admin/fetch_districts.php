<?php
include('include/config.php');

if (isset($_POST['state'])) {
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $query = "SELECT DISTINCT district FROM cities WHERE state = '$state' ORDER BY district ASC";
    $result = mysqli_query($conn, $query);

    echo '<option value="">-- Select District --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . htmlspecialchars($row['district']) . '">' . htmlspecialchars($row['district']) . '</option>';
    }
}
?>
