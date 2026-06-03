<?php
include('include/config.php');

/* ==============================
   FETCH PROJECT DATA
============================== */
if (!isset($_GET['updateid'])) {
    die('Invalid Request');
}

$id = mysqli_real_escape_string($conn, $_GET['updateid']);

$query  = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (!$row = mysqli_fetch_assoc($result)) {
    die('Project not found');
}

/* Assign variables */
$client_name     = $row['client_name'];
$project_name    = $row['project_name'];
$work_ord_no     = $row['work_ord_no'];
$sale_ord_no     = $row['sale_ord_no'];
$project_starts  = $row['project_starts'];
$project_end     = $row['project_end'];
$project_status  = $row['project_status'];
$remark           = $row['remark'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Project</title>

</head>

<body>
<div class="container mt-5">


<form id="projectForm" method="POST" action="update_project.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id; ?>">
    <!-- CLIENT -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Client Name</label>
        <div class="col-md-9">
            <select name="client_name" class="form-control" required>
                <option value="">-- Select Client --</option>
                <?php
                $clients = mysqli_query($conn, "SELECT client_name FROM clients ORDER BY client_name");
                while ($c = mysqli_fetch_assoc($clients)) {
                    $selected = ($c['client_name'] === $client_name) ? 'selected' : '';
                    echo "<option value='".htmlspecialchars($c['client_name'])."' $selected>
                            ".htmlspecialchars($c['client_name'])."
                          </option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- PROJECT NAME -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Project Name</label>
        <div class="col-md-9">
            <input type="text" name="project_name" id="project_name" maxlength="50"
                   class="form-control"
                   value="<?= htmlspecialchars($project_name); ?>" required>
        </div>
    </div>

    <!-- WORK ORDER -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Work Order No</label>
        <div class="col-md-9">
            <input type="text" name="work_ord_no" id="work_ord_no"  maxlength="50"
                   class="form-control"
                   value="<?= htmlspecialchars($work_ord_no); ?>">
        </div>
    </div>

    <!-- SALE ORDER -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Sale Order No</label>
        <div class="col-md-9">
            <input type="text" name="sale_ord_no" id="sale_ord_no" required maxlength="50"
                   class="form-control"
                   value="<?= htmlspecialchars($sale_ord_no); ?>">
        </div>
    </div>

    <!-- START DATE -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Project Start Date</label>
        <div class="col-md-9">
            <input type="date" name="project_starts" id="project_starts"
                   class="form-control"
                   value="<?= htmlspecialchars($project_starts); ?>" required>
        </div>
    </div>

    <!-- STATUS -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Project Status</label>
        <div class="col-md-9">
            <select name="project_status" id="project_status" class="form-control" required>
                <?php
                $statuses = ['Ongoing','Completed','Upcoming','On Hold'];
                foreach ($statuses as $s) {
                    $sel = ($s === $project_status) ? 'selected' : '';
                    echo "<option value='$s' $sel>$s</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- END DATE -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Project End Date</label>
        <div class="col-md-9">
            <input type="date" name="project_end" id="project_end"
                   class="form-control"
                   value="<?= htmlspecialchars($project_end); ?>">
        </div>
    </div>

    <!-- REMARK -->
    <div class="row mb-3">
        <label class="col-md-3 fw-bold">Remark</label>
        <div class="col-md-9">
            <textarea name="remark" class="form-control" rows="3"><?= htmlspecialchars($remark); ?></textarea>
        </div>
    </div>

    <!-- BUTTONS -->
    <div class="text-center mt-4">
        <button type="submit" name="update" class="btn btn-primary px-4">Update</button>
        <a href="list_project.php" class="btn btn-secondary px-4 ms-2">Back</a>
    </div>

</form>

</div>


<script>
const status   = document.getElementById('project_status');
const endDate  = document.getElementById('project_end');
const startDate = document.getElementById('project_starts');

/* Status control */
function toggleEndDate() {
    if (status.value === 'Completed') {
        endDate.disabled = false;
        endDate.required = true;
    } else {
        endDate.disabled = true;
        endDate.required = false;
        endDate.value = '';
    }
}
toggleEndDate();
status.addEventListener('change', toggleEndDate);

/* Min end date */
startDate.addEventListener('change', function () {
    endDate.min = this.value;
});

/* Date validation */
document.getElementById('projectForm').addEventListener('submit', function (e) {
    if (endDate.value && endDate.value < startDate.value) {
        alert('Project End date cannot be before Start date!');
        endDate.focus();
        e.preventDefault();
    }
});
</script>

</body>
</html>
