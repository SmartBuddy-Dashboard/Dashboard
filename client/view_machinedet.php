<?php
include_once('include/config.php');

$machine_id = $_GET['id']; // Get the ID from the URL parameter

$query = "SELECT * FROM datatest WHERE machine_id = '$machine_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">Machine ID</th>
                <th class="text-center">Machine Use</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $machine_id    = $row["machine_id"];
                $received_time = $row["received_time"];
                ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($machine_id ?? ''); ?></td>
                    <td class="text-center">
    <?php echo date("d-m-Y H:i:s", strtotime($received_time)); ?>
</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
} else {
    echo "<p>No record found for this Machine ID.</p>";
}
?>
