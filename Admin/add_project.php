<?php
require_once('include/header.php');
require_once('include/navbar.php');
require_once('include/config.php');


/* ==============================
   FORM SUBMIT
============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $client_name       = mysqli_real_escape_string($conn, $_POST['client_name']);
    $project_name    = mysqli_real_escape_string($conn, $_POST['project_name']);
    $work_ord_no     = mysqli_real_escape_string($conn, $_POST['work_ord_no']);
    $sale_ord_no     = mysqli_real_escape_string($conn, $_POST['sale_ord_no']);
    $project_starts  = mysqli_real_escape_string($conn, $_POST['project_starts']);
    $project_end     = !empty($_POST['project_end']) 
                        ? mysqli_real_escape_string($conn, $_POST['project_end']) 
                        : NULL;
    $project_status  = mysqli_real_escape_string($conn, $_POST['project_status']);
    $remark          = mysqli_real_escape_string($conn, $_POST['remark']);

    /* ----- DUPLICATE CHECK ----- */
    $check = mysqli_query($conn, "
        SELECT id FROM projects
        WHERE project_name = '$project_name'
        AND client_name = '$client_name'
        AND project_starts = '$project_starts'
        LIMIT 1
    ");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Project already exists!'); window.location='list_project.php';</script>";
        exit;
    }

    /* ----- INSERT ----- */
    $sql = "INSERT INTO projects 
    (client_name, project_name, work_ord_no, sale_ord_no, project_starts, project_end, project_status, remark)
    VALUES (
        '$client_name',
        '$project_name',
        '$work_ord_no',
        '$sale_ord_no',
        '$project_starts',
        ".($project_end ? "'$project_end'" : "NULL").",
        '$project_status',
        '$remark'
    )";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Project added successfully!'); window.location='list_project.php';</script>";
    } else {
        echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
    }
}
?>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
<div class="container-fluid">

<div class="card shadow mb-4">
<div class="card-header py-3">
    <h5 class="text-center"><b>Add Project Details</b></h5>
</div>

<div class="card-body">

<form id="myForm" method="POST">

<div class="container">

<!-- CLIENT NAME -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Client Name:</label>
    <div class="col-md-6">
        <select class="form-control" name="client_name" required>
    <option value="">-- Select Client --</option>
    <?php 
    $clients = mysqli_query($conn, "
        SELECT client_name 
        FROM clients 
        ORDER BY client_name
    ");
    while ($c = mysqli_fetch_assoc($clients)) { ?>
        <option value="<?= htmlspecialchars($c['client_name']); ?>">
            <?= htmlspecialchars($c['client_name']); ?>
        </option>
    <?php } ?>
</select>

    </div>
</div>

<!-- PROJECT NAME -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Project Name:</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="project_name" maxlength="50" required>
    </div>
</div>

<!-- WORK ORDER NO -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Work Order No:</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="work_ord_no" maxlength="50">
    </div>
</div>

<!-- SALE ORDER NO -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Sale Order No:</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="sale_ord_no" maxlength="50" required>
    </div>
</div>

<!-- START DATE -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Project Start Date:</label>
    <div class="col-md-6">
        <input type="date" class="form-control" name="project_starts" id="project_starts" required>
    </div>
</div>
<!-- PROJECT STATUS -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Project Status:</label>
    <div class="col-md-6">
        <select class="form-control" name="project_status" id="project_status" required>
            <option value="">-- Select Status --</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="Upcoming">Upcoming</option>
            <option value="On Hold">On Hold</option>
        </select>
    </div>
</div>

<!-- COMPLETED DATE -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Project Completed Date:</label>
    <div class="col-md-6">
        <input type="date" class="form-control" name="project_end" id="project_end" disabled >
    </div>
</div>



<!-- REMARK -->
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Remarks:</label>
    <div class="col-md-6">
        <textarea class="form-control" name="remark" rows="3"></textarea>
    </div>
</div>

</div>

<div class="text-center mt-4">
    <button type="submit" id="submitBtn" class="btn btn-success">Submit</button>
    <a href="list_project.php" class="btn btn-secondary ms-2">Back</a>
</div>

</form>
</div>
</div>

</div>
</div>
</div>

<?php
include('include/scripts.php');
include('include/footer.php');
?>
<script>
const form = document.getElementById('myForm');
const submitBtn = document.getElementById('submitBtn');
const status = document.getElementById('project_status');
const endDate = document.getElementById('project_end');

/* STATUS CHANGE */
status.addEventListener('change', function () {

    if (this.value === 'Completed') {
        endDate.disabled = false;
        endDate.required = true;   // ✅ REQUIRED
    } else {
        endDate.disabled = true;
        endDate.required = false;  // ❌ NOT REQUIRED
        endDate.value = '';
    }
});

/* FORM SUBMIT */
form.addEventListener('submit', function (e) {

    if (!validateProjectDates()) {
        e.preventDefault();
        submitBtn.disabled = false;
        submitBtn.innerText = 'Submit';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerText = 'Submitting...';
});

/* DATE VALIDATION */
function validateProjectDates() {
    const start = document.getElementById("project_starts").value;
    const end   = document.getElementById("project_end").value;
    const statusVal = status.value;

    // 🔒 If Completed → end date mandatory
    if (statusVal === 'Completed' && !end) {
        alert("Project Completed date is required when status is Completed!");
        endDate.focus();
        return false;
    }

    // 🔒 Date comparison
    if (start && end && end < start) {
        alert("Project Completed date cannot be earlier than Project Start date!");
        endDate.value = '';
        endDate.focus();
        return false;
    }

    return true;
}
</script>


