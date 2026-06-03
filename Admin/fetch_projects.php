<?php
include('include/config.php');

$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);

// Fetch projects for the selected client
$sql = "SELECT project_name, sale_ord_no, project_starts
        FROM projects
        WHERE client_name = '$client_name'";
$result = mysqli_query($conn, $sql);

$options = '<option value="">-- Select Sale Order --</option>';

while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="'.htmlspecialchars($row['project_name']).'"
                    data-projectdate="'.htmlspecialchars($row['project_starts']).'">'
                .htmlspecialchars($row['sale_ord_no']).'
                </option>';
}

echo $options;
?>
