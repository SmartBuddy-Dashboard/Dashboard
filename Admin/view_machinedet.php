<?php
include_once('include/config.php');

$machine_id = $_GET['id']; // Get Machine ID

$query = "SELECT * FROM datatest WHERE machine_id = '$machine_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
?>

<style>
.machine-use {
  padding: 10px;
}

/* Desktop */
.machine-use table {
  width: 80%;
  margin: auto;
  font-size: 14px;
}

/* ================= MOBILE VIEW ================= */
@media (max-width: 768px) {

  .machine-use table {
    width: 100%;
    font-size: 13.5px;
  }

  .machine-use thead {
    display: none;
  }

  .machine-use table,
  .machine-use tbody,
  .machine-use tr,
  .machine-use td {
    display: block;
    width: 100%;
  }

  .machine-use tr {
    margin-bottom: 12px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 6px;
  }

  .machine-use td {
    padding: 8px 10px;
    text-align: left !important;
  }

  .machine-use td::before {
    content: attr(data-label);
    font-weight: 600;
    display: block;
    margin-bottom: 2px;
    color: #555;
  }
}
</style>

<div class="machine-use">

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center">Machine ID</th>
            <th class="text-center">Machine Use Time</th>
        </tr>
    </thead>
    <tbody>

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $machine_id    = $row["machine_id"];
        $received_time = $row["received_time"];
    ?>
        <tr>
            <td class="text-center" data-label="Machine ID">
                <?= htmlspecialchars($machine_id ?? ''); ?>
            </td>
            <td class="text-center" data-label="Machine Use Time">
                <?= date("d-m-Y H:i:s", strtotime($received_time)); ?>
            </td>
        </tr>
    <?php } ?>

    </tbody>
</table>

</div>

<?php
} else {
    echo "<p class='text-center text-muted'>No record found for this Machine ID.</p>";
}
?>
