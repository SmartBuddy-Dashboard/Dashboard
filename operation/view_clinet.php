<?php
include_once('include/config.php');

$id = $_GET['id']; 

$query = "SELECT * FROM clients WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {

    $contact_person   = $row["contact_person"];
    $client_name      = $row["client_name"];
    $contact_mobile   = $row["contact_mobile"];
    $client_phone     = $row["client_phone"];
    $client_address   = $row["client_address"];
    $client_website   = $row["client_website"];
    $state            = $row["client_state"];
    $district         = $row["clinet_district"];
    $city             = $row["client_city"];
    $client_type      = $row["client_type"];
    $contact_email    = $row["contact_email"];
    $client_logo = $row["client_logo"];
    
?>

<style>
/* Wrapper */
.client-view {
  padding: 10px;
}

/* Desktop */
.client-view table {
  width: 70%;
  margin: auto;
  font-size: 14px;
}

.client-view th[colspan="2"] {
  background: #f5f5f5;
  font-size: 18px;
  text-align: center;
}

/* ================= MOBILE VIEW ================= */
@media (max-width: 768px) {

  .client-view table {
    width: 100% !important;
    font-size: 13.5px; /* ✅ slightly bigger */
  }

  .client-view table,
  .client-view tbody,
  .client-view tr,
  .client-view td,
  .client-view th {
    display: block;
    width: 100%;
  }

  .client-view tr {
    margin-bottom: 10px;   /* balanced spacing */
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
  }

  .client-view td {
    padding: 7px 10px;     /* balanced padding */
    line-height: 1.4;
    text-align: left;
  }

  .client-view td:first-child {
    font-weight: 600;
    background: #f8f9fa;
    font-size: 13.5px;
  }

  .client-view th[colspan="2"] {
    font-size: 15px;       /* section title slightly bigger */
    padding: 8px;
  }
}
</style>

<div class="table-responsive client-view">

<table class="table table-bordered" cellpadding="8" cellspacing="0">

    <tr>
        <td><strong>Client Name</strong></td>
        <td><?= htmlspecialchars($client_name ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Client Phone</strong></td>
        <td><?= htmlspecialchars($client_phone ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Client Address</strong></td>
        <td><?= htmlspecialchars($client_address ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Client Website</strong></td>
        <td><?= htmlspecialchars($client_website ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Client Type</strong></td>
        <td><?= htmlspecialchars($client_type ?? ''); ?></td>
    </tr>

    <tr>
        <th colspan="2">Location Details</th>
    </tr>

    <tr>
        <td><strong>State</strong></td>
        <td><?= htmlspecialchars($state ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>District</strong></td>
        <td><?= htmlspecialchars($district ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>City</strong></td>
        <td><?= htmlspecialchars($city ?? ''); ?></td>
    </tr>

    <tr>
        <th colspan="2">Contact Person Details</th>
    </tr>

    <tr>
        <td><strong>Contact Person</strong></td>
        <td><?= htmlspecialchars($contact_person ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Contact Mobile</strong></td>
        <td><?= htmlspecialchars($contact_mobile ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Contact Email</strong></td>
        <td><?= htmlspecialchars($contact_email ?? ''); ?></td>
    </tr>
    <tr>
    <td><strong>Client Logo</strong></td>
    <td>
        <?php if (!empty($client_logo) && file_exists("uploads/".$client_logo)) { ?>
            <img src="uploads/<?= htmlspecialchars($client_logo ?? ''); ?>"
                 alt="Client Logo"
                 style="max-height:80px; border:1px solid #ccc; padding:5px;">
        <?php } else { ?>
            <span class="text-muted">No Logo Available</span>
        <?php } ?>
    </td>
</tr>

    

</table>

</div>

<?php
} else {
    echo "<h4 class='text-danger text-center'>Error retrieving client details.</h4>";
}
?>
