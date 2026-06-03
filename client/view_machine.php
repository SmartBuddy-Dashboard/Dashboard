<?php
include_once('include/config.php');

$id = intval($_GET['id']); // Safe integer ID

$query = "SELECT * FROM machines WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {

    $machine_id        = $row["machine_id"];
    $client_name       = $row["client_name"];
    $state             = $row["state"];
    $district          = $row["district"];
    $city              = $row["city"];
    $address           = $row["address"];
    $status            = $row["status"];
    $uses_amt          = $row["uses_amt"];
    $project_name      = $row["project_name"];
    $po_date           = $row["po_date"];
    $installation_date = $row["installation_date"];

    $wall_clean   = $row['wall_clean'];
    $seats        = $row['seats'];
    $flush_time   = $row['flush_time'];
    $floor_time   = $row['floor_time'];
    $wall_time    = $row['wall_time'];

    $free          = $row['free'];
    $coin          = $row['coin'];
    $upi           = $row['upi'];
    $smart_card    = $row['smart_card'];
    $digital_token = $row['digital_token'];

    $qr_code = $row["qr_code"];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Machine Details</title>

<style>
.machine-view {
  padding: 10px;
}

/* Desktop */
.machine-view table {
  width: 70%;
  margin: auto;
  font-size: 14px;
}

/* ================= MOBILE VIEW ================= */
@media (max-width: 768px) {

  .machine-view table {
    width: 100%;
    font-size: 13.5px;
  }

  .machine-view table,
  .machine-view tbody,
  .machine-view tr,
  .machine-view td {
    display: block;
    width: 100%;
  }

  .machine-view tr {
    margin-bottom: 10px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
  }

  .machine-view td {
    padding: 7px 10px;
    line-height: 1.4;
  }

  .machine-view td:first-child {
    font-weight: 600;
    background: #f8f9fa;
  }

  .machine-view img {
    max-width: 120px;
    height: auto;
  }
}
</style>
</head>

<body>

<div class="machine-view">

<table class="table table-bordered" cellpadding="8" cellspacing="0">

    <tr><td><strong>Machine ID</strong></td><td><?= htmlspecialchars($machine_id ?? ''); ?></td></tr>
    <tr><td><strong>Client Name</strong></td><td><?= htmlspecialchars($client_name ?? ''); ?></td></tr>
    <tr><td><strong>State</strong></td><td><?= htmlspecialchars($state ?? ''); ?></td></tr>
    <tr><td><strong>District</strong></td><td><?= htmlspecialchars($district ?? ''); ?></td></tr>
    <tr><td><strong>City</strong></td><td><?= htmlspecialchars($city ?? ''); ?></td></tr>
    <tr><td><strong>Address</strong></td><td><?= htmlspecialchars($address ?? ''); ?></td></tr>

    <tr><td><strong>Project Name</strong></td><td><?= htmlspecialchars($project_name ?? ''); ?></td></tr>
    <tr><td><strong>PO Date</strong></td><td><?= htmlspecialchars($po_date ?? ''); ?></td></tr>
    <tr><td><strong>Installation Date</strong></td><td><?= htmlspecialchars($installation_date ?? ''); ?></td></tr>

    <tr><td><strong>Uses Amount(Rs.)</strong></td><td><?= htmlspecialchars($uses_amt ?? ''); ?></td></tr>
    <tr><td><strong>Status</strong></td><td><?= htmlspecialchars($status ?? ''); ?></td></tr>

    <tr><td><strong>Wall Clean</strong></td><td><?= htmlspecialchars($wall_clean ?? ''); ?></td></tr>
    <tr><td><strong>Seats(After)</strong></td><td><?= htmlspecialchars($seats ?? ''); ?></td></tr>
    <tr><td><strong>Flush Time(Sec.)</strong></td><td><?= htmlspecialchars($flush_time ?? ''); ?></td></tr>
    <tr><td><strong>Floor Time(Sec.)</strong></td><td><?= htmlspecialchars($floor_time ?? ''); ?></td></tr>
    <tr><td><strong>Wall Time(Sec.)</strong></td><td><?= htmlspecialchars($wall_time ?? ''); ?></td></tr>

    <tr>
        <td><strong>Payment Modes</strong></td>
        <td>
            Free: <?= htmlspecialchars($free ?? ''); ?><br>
            Coin: <?= htmlspecialchars($coin ?? ''); ?><br>
            UPI: <?= htmlspecialchars($upi ?? ''); ?><br>
            Smart Card: <?= htmlspecialchars($smart_card ?? ''); ?><br>
            Digital Token: <?= htmlspecialchars($digital_token ?? ''); ?>
        </td>
    </tr>

    <tr>
        <td><strong>QR Code</strong></td>
        <td>
            <?php if (!empty($qr_code)) { ?>
                <img src="data:image/png;base64,<?= base64_encode($qr_code); ?>" alt="QR Code">
            <?php } else { ?>
                <span style="color:red;">QR Code Not Generated</span>
            <?php } ?>
        </td>
    </tr>

</table>

</div>

</body>
</html>

<?php
} else {
    echo "<h4 style='color:red;text-align:center;'>Error retrieving machine details.</h4>";
}
?>
