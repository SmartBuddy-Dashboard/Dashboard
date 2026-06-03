<?php
include_once('include/config.php');

$machine_id = $_GET['machine_id'];

$query = "SELECT * FROM machines WHERE machine_id = '$machine_id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {

$machine_id        = $row["machine_id"];
$client_name       = $row["client_name"];
$state             = $row["state"];
$district          = $row["district"];
$city              = $row["city"];
$address           = $row["address"];
$inst_address      = $row["inst_address"];
$status            = $row["status"];
$uses_amt          = $row["uses_amt"];
$project_name      = $row["project_name"];
$po_date           = $row["po_date"];
$installation_date = $row["installation_date"];
$dispatch_date     = $row["dispatch_date"];
$wall_clean        = $row['wall_clean'];
$seats             = $row['seats'];
$flush_time        = $row['flush_time'];
$floor_time        = $row['floor_time'];
$wall_time         = $row['wall_time'];

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

body {
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

.machine-card {
    max-width: 950px;
    margin: 40px auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.08);
    padding: 30px;
}

.machine-title {
    text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    color: #333;
}

.machine-table {
    width: 100%;
    border-collapse: collapse;
}

.machine-table td {
    padding: 11px 14px;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

.machine-table td:first-child {
    width: 35%;
    font-weight: 600;
    color: #444;
    background: #fafafa;
}

.machine-table tr:hover {
    background: #f9fbff;
}

/* Status Badge */
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
    display: inline-block;
}

.status-active {
    background: #28a745;
}

.status-inactive {
    background: #dc3545;
}

/* Access Badges */
.access-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 12px;
    margin: 2px 4px 2px 0;
    background: #e9ecef;
}

/* QR */
.qr-box {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 8px;
    display: inline-block;
    background: #fff;
}

.qr-box img {
    max-width: 150px;
}
.status-ready {
    background: #28a745;  /* Green */
}

.status-maintenance {
    background: #dc3545;  /* Red */
}

.status-default {
    background: #6c757d;  /* Grey for other status */
}

/* ================= MOBILE ================= */
@media (max-width: 768px) {

.machine-card {
    margin: 15px;
    padding: 20px;
}

.machine-table,
.machine-table tbody,
.machine-table tr,
.machine-table td {
    display: block;
    width: 100%;
}

.machine-table tr {
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.machine-table td {
    padding: 6px 0;
}

.machine-table td:first-child {
    background: none;
    font-size: 13px;
    color: #777;
}

.machine-table td:last-child {
    font-size: 14px;
    font-weight: 500;
}

.qr-box img {
    max-width: 120px;
}

}

</style>
</head>

<body>

<div class="machine-card">



<table class="machine-table">

<tr><td>Machine ID</td><td><?= htmlspecialchars($machine_id ?? ''); ?></td></tr>
<tr><td>Client Name</td><td><?= htmlspecialchars($client_name ?? ''); ?></td></tr>
<tr><td>State</td><td><?= htmlspecialchars($state ?? ''); ?></td></tr>
<tr><td>District</td><td><?= htmlspecialchars($district ?? ''); ?></td></tr>
<tr><td>City</td><td><?= htmlspecialchars($city ?? ''); ?></td></tr>
<tr><td>Address</td><td><?= htmlspecialchars($address ?? ''); ?></td></tr>

<tr><td>Project Name</td><td><?= htmlspecialchars($project_name ?? ''); ?></td></tr>

<tr>
<td>Project Date</td>
<td><?= !empty($po_date) ? date('d-m-Y', strtotime($po_date)) : '-' ?></td>
</tr>

<tr>
<td>Installation Date</td>
<td><?= !empty($installation_date) ? date('d-m-Y', strtotime($installation_date)) : '-' ?></td>
</tr>
<tr>
    <td>Machine Installation Address</td>
    <td><?= !empty($inst_address) ? htmlspecialchars($inst_address ?? '') : '-' ?></td>
</tr>


<tr>
<td>Dispatch Date</td>
<td><?= !empty($dispatch_date) ? date('d-m-Y', strtotime($dispatch_date)) : '-' ?></td>
</tr>

<tr>
<td>Entry Fee</td>
<td>
<?php
if ($free === 'Yes') {
    echo "Button";
} else {
    echo "₹" . htmlspecialchars($uses_amt ?? '');
}
?>
</td>
</tr>

<tr>
<td>Machine Mode</td>
<td>
<?php
$statusLower = strtolower($status);

if ($statusLower == 'ready') {
    echo '<span class="status-badge status-ready">Ready</span>';
} elseif ($statusLower == 'maintenance') {
    echo '<span class="status-badge status-maintenance">Maintenance</span>';
} else {
    echo '<span class="status-badge status-default">' . htmlspecialchars(ucfirst($status)) . '</span>';
}
?>
</td>
</tr>


<tr>
<td>Wall Cleaning Mode</td>
<td>
<?= ($wall_clean === 'En') ? 'Enable' : (($wall_clean === 'Dis') ? 'Disable' : htmlspecialchars($wall_clean ?? '')); ?>
</td>
</tr>

<tr><td>Wall Cleaning After Users</td><td><?= htmlspecialchars($seats ?? ''); ?> Users</td></tr>
<tr><td>Flush Time</td><td><?= htmlspecialchars($flush_time ?? ''); ?> Sec</td></tr>
<tr><td>Floor Cleaning Time</td><td><?= htmlspecialchars($floor_time ?? ''); ?> Sec</td></tr>
<tr><td>Wall Cleaning Time</td><td><?= htmlspecialchars($wall_time ?? ''); ?> Sec</td></tr>

<tr>
<td>Access Mode</td>
<td>
<span class="access-badge">Button: <?= htmlspecialchars($free ?? ''); ?></span>
<span class="access-badge">Coin: <?= htmlspecialchars($coin ?? ''); ?></span>
<span class="access-badge">UPI: <?= htmlspecialchars($upi ?? ''); ?></span>
<span class="access-badge">Smart Card: <?= htmlspecialchars($smart_card ?? ''); ?></span>
<span class="access-badge">Digital Token: <?= htmlspecialchars($digital_token ?? ''); ?></span>
</td>
</tr>

<tr>
<td>QR Code</td>
<td>
<?php if (!empty($qr_code)) { ?>
<div class="qr-box">
<img src="data:image/png;base64,<?= base64_encode($qr_code); ?>" alt="QR Code">
</div>
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
