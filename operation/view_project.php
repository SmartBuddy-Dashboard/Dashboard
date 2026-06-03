<?php
include_once('include/config.php');

/* ===============================
   GET & VALIDATE ID
================================ */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h3 style='color:red; text-align:center;'>Invalid Project ID</h3>";
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* ===============================
   FETCH PROJECT DETAILS
================================ */
$query  = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {

    $project_name    = $row['project_name'];
    $client_name     = $row['client_name'];
    $work_ord_no     = $row['work_ord_no'];
    $sale_ord_no     = $row['sale_ord_no'];
    $project_starts  = $row['project_starts'];
    $project_end     = $row['project_end'];
    $remark          = $row['remark'];
    $project_status  = $row['project_status'];
?>
    
<table class="table table-bordered" 
       style="width:70%; margin:auto; margin-top:30px;">

    <tbody>
        <tr>
            <th style="width:30%;">Client Name</th>
            <td><?= htmlspecialchars($client_name ?? ''); ?></td>
        </tr>

        <tr>
            <th>Project Name</th>
            <td><?= htmlspecialchars($project_name ?? ''); ?></td>
        </tr>

        <tr>
            <th>Work Order No</th>
            <td><?= htmlspecialchars($work_ord_no ?? ''); ?></td>
        </tr>

        <tr>
            <th>Sale Order No</th>
            <td><?= htmlspecialchars($sale_ord_no ?? ''); ?></td>
        </tr>

        <tr>
            <th>Project Start Date</th>
            <td><?= date("d-m-Y", strtotime($project_starts)); ?></td>
        </tr>

        <tr>
            <th>Project End Date</th>
            <td>
                <?= !empty($project_end)
                    ? date("d-m-Y", strtotime($project_end))
                    : '<span class="text-muted">Not Completed</span>'; ?>
            </td>
        </tr>

        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($project_status ?? ''); ?></td>
        </tr>

        <tr>
            <th>Remark</th>
            <td><?= nl2br(htmlspecialchars($remark ?? '')); ?></td>
        </tr>
    </tbody>
</table>

<div class="text-center mt-4">
    <a href="list_project.php" class="btn btn-secondary">Back</a>
</div>

<?php
} else {
    echo "<h3 style='color:red; text-align:center;'>Project not found.</h3>";
}
?>
