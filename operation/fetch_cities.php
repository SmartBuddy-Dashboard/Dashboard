<?php
include('include/config.php');

if (isset($_POST['district'])) {
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $query = "SELECT DISTINCT city FROM cities WHERE district = '$district' ORDER BY city ASC";
    $result = mysqli_query($conn, $query);

    echo '<option value="">-- Select City / Taluka --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . htmlspecialchars($row['city']) . '">' . htmlspecialchars($row['city']) . '</option>';
    }
}
?>
