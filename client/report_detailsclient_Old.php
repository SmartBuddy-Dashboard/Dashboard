<?php 
require_once('include/header.php');
require_once('include/config.php');
require_once('include/navbar.php');

// ✅ Check session variables properly
if (!isset($_SESSION['mobile']) || !isset($_SESSION['client_name'])) {
   
    header("Location: index.php"); // Redirect to login page if not set
    exit();
}




$selectedClient = $_SESSION['client_name'];


$selectedProject = $_POST['project_name'] ?? '';
$selectedMachine = $_POST['machine_id']   ?? '';

$filter_type = $_POST['filter_type'] ?? 'fy'; // Default to 'fy'

// Only get Year_select value if filter_type is 'fy', otherwise set to empty
if($filter_type == 'fy') {
    $selectedYear = $_POST['Year_select'] ?? 'current';
} else {
    $selectedYear = ''; // Force empty when date range is selected
}

$from_date = $_POST['from_date'] ?? '';
$to_date   = $_POST['to_date'] ?? '';

/* ================= FINANCIAL YEAR LOGIC ================= */
$currentYear  = date('Y');
$currentMonth = date('m');

if($currentMonth < 4){
    $fyStart = ($currentYear - 1) . "-04-01";
    $fyEnd   = $currentYear . "-03-31";
} else {
    $fyStart = $currentYear . "-04-01";
    $fyEnd   = ($currentYear + 1) . "-03-31";
}

/* ================= APPLY FY ================= */
// Only auto-set dates if filter_type is 'fy' AND selectedYear is not empty
if($filter_type == 'fy' && !empty($selectedYear)) {
    if($selectedYear == "current"){
        $from_date = $fyStart;
        $to_date   = $fyEnd;
    }
    elseif($selectedYear == "last"){
        $from_date = date('Y-m-d', strtotime("$fyStart -1 year"));
        $to_date   = date('Y-m-d', strtotime("$fyEnd -1 year"));
    }
    elseif($selectedYear == "previous"){
        $from_date = date('Y-m-d', strtotime("$fyStart -2 year"));
        $to_date   = date('Y-m-d', strtotime("$fyEnd -2 year"));
    }
}
// If filter_type is 'date' or selectedYear is empty, keep the posted manual dates

$clientName = '';
$clientLogo = '';

if (!empty($selectedProject)) {
    // Escape input
    $selectedProject = mysqli_real_escape_string($conn, $selectedProject);

    // Fetch client name and logo
    $cq = "SELECT client_name, client_logo 
           FROM clients 
           WHERE client_name = '$selectedProject' 
           LIMIT 1";

    $cr = mysqli_query($conn, $cq);

    if ($crow = mysqli_fetch_assoc($cr)) {
        $clientName = $crow['client_name'];

        // Prepend 'uploads/' since DB stores only filename
        if (!empty($crow['client_logo'])) {
            $clientLogo = 'uploads/' . $crow['client_logo'];
        }
    }
}
?>
<head>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
#userTable, #userTable1 {
    border: 2px solid #ddd !important;
}

#userTable th, #userTable td,
#userTable1 th, #userTable1 td {
    border: 1px solid #ddd !important;
}

#userTable tbody tr:nth-child(odd),
#userTable1 tbody tr:nth-child(odd) {
    
}

.dt-btn-custom {
    padding: 8px 15px;
    border-radius: 8px;
     !important;
    border: 2px solid #000 !important;
    color: #000 !important;
    font-size: 13px;
    font-weight: 600;
    margin-right: 6px;
    transition: .3s;
}

.dt-btn-custom:hover {
    border-color: #00b894 !important;
    color: #00b894 !important;
}
 /* Reset custom active so Bootstrap color stays */
.button-group .btn.active {
  filter: brightness(90%);   /* slightly darker to show "active" */
  box-shadow: 0 0 6px rgba(0,0,0,0.3);
}

/* FY Buttons */
    .fy-buttons { display: flex; gap: 15px; flex-wrap: wrap; }
    .fy-btn {
      padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 600;
      transition: all 0.3s; display: flex; align-items: center; gap: 8px;
    }
    .fy-btn.current { background: linear-gradient(135deg, #00d9a5, #00b894); color: #fff; box-shadow: 0 5px 20px rgba(0,217,165,0.3); }
    .fy-btn.last { background: linear-gradient(135deg, #3498db, #2980b9); color: #fff; box-shadow: 0 5px 20px rgba(52,152,219,0.3); }
    .fy-btn.previous { background: linear-gradient(135deg, #6c757d, #5a6268); color: #fff; box-shadow: 0 5px 20px rgba(108,117,125,0.3); }
    .fy-btn:hover { transform: translateY(-2px); }
    .dataTables_wrapper {
    width: 100% !important;
    overflow-x: hidden !important;
}
/* =========================================
   MOBILE ONLY – ENABLE HORIZONTAL SCROLL
========================================= */

@media screen and (max-width: 768px) {

    .table-responsive {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .dataTables_wrapper {
        overflow-x: auto !important;
    }

    /* Force table wider than screen */
  #userTable,
#userTable1 {
    min-width: 800px;   /* forces scrollbar */
    width: 100%;
    table-layout: auto;
}

#userTable th,
#userTable td,
#userTable1 th,
#userTable1 td {
    white-space: nowrap;
}

    /* Scrollbar style */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
}
/* KPI row */
        .kpi-grid { background: transparent;
           
        }

        .kpi-card { background: var(--bg-card);
            border-radius: 12px;
            padding: 16px 10px;
            border-left: 4px solid #1f4b77;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .kpi-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #4d6278;
            letter-spacing: 0.3px;
        }

        .kpi-value {
            font-size: 1.9rem;
            font-weight: 700;
            color: #0b2b4a;
            line-height: 1.2;
        }


/* =========================================
   DESKTOP ONLY – NO HORIZONTAL SCROLL
========================================= */

@media screen and (min-width: 769px) {

        .table-responsive,
.dataTables_wrapper {
    overflow-x: visible !important;
}

  #userTable,
#userTable1 {
    min-width: 100%;
    table-layout: fixed;
}

#userTable th,
#userTable td,
#userTable1 th,
#userTable1 td {
    white-space: normal;
}

}

</style>
</head>

<div class="container-fluid mt-3">
<div class="card shadow">
    <div class="card-header text-center">
        <h5><b>E‑Toilet Business Performance Report</b></h5>
    </div>

    <div class="card-body">

<form method="POST" id="complaintForm">

<div class="row">

    <!-- CLIENT NAME -->
    

    <div class="col-md-4">
        <label><b>Project Name</b></label>
        <select name="project_name" class="form-control" onchange="submitForm()">
            <option value="">All Project</option>
            <?php
            if(!empty($selectedClient)){
                $selectedClient = mysqli_real_escape_string($conn,$selectedClient);
                $pq = mysqli_query($conn,"
                    SELECT DISTINCT project_name 
                    FROM projects
                    WHERE client_name='$selectedClient'
                ");
            } else {
                $pq = mysqli_query($conn,"
                    SELECT DISTINCT project_name 
                    FROM projects
                ");
            }

            while($prow=mysqli_fetch_assoc($pq)){
                $pname = $prow['project_name'];
                $sel   = ($pname==$selectedProject) ? "selected" : "";
                echo "<option value='$pname' $sel>$pname</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-4">
        <label><b>Machine ID:</b></label>
        <select name="machine_id" class="form-control" onchange="submitForm()">
            <option value="">All Machine</option>
            <?php
            $machineQuery = "SELECT DISTINCT machine_id FROM machines WHERE 1";
            if(!empty($selectedClient)){
                $machineQuery .= " AND client_name='$selectedClient'";
            }
            if(!empty($selectedProject)){
                $machineQuery .= " AND project_name='$selectedProject'";
            }
            $mq = mysqli_query($conn,$machineQuery);
            while($mrow=mysqli_fetch_assoc($mq)){
                $mid = $mrow['machine_id'];
                $sel = ($mid==$selectedMachine) ? "selected" : "";
                echo "<option value='$mid' $sel>$mid</option>";
            }
            ?>
        </select>
    </div>

    <div class="col-md-4">
        <label><b>Filter Type</b></label><br>
        <label>
            <input type="radio" name="filter_type" value="fy"
                <?= ($filter_type == 'fy') ? 'checked' : '' ?>
                onclick="toggleFilter()">
            Financial Year
        </label>
        <label style="margin-left:15px;">
            <input type="radio" name="filter_type" value="date"
                <?= ($filter_type == 'date') ? 'checked' : '' ?>
                onclick="toggleFilter()">
            Date Range
        </label>
    </div>
</div>

<br>

<div class="row">

    <!-- Financial Year Section -->
    <div class="col-md-3" id="fy_section">
        <label><b>Financial Year</b></label>
        <select name="Year_select" id="Year_select" class="form-control" onchange="submitForm()">
            <option value="current" <?= ($selectedYear=='current') ? 'selected' : ''; ?>>Current FY</option>
            <option value="last" <?= ($selectedYear=='last') ? 'selected' : ''; ?>>Last FY</option>
            <option value="previous" <?= ($selectedYear=='previous') ? 'selected' : ''; ?>>Previous FY</option>
        </select>
    </div>

    <!-- From Date -->
    <div class="col-md-3">
        <label><b>From Date</b></label>
        <input type="date" name="from_date" id="from_date"
            class="form-control"
            value="<?= htmlspecialchars($from_date) ?>"
            <?= ($filter_type == 'fy') ? 'readonly' : '' ?>>
    </div>

    <!-- To Date -->
    <div class="col-md-3">
        <label><b>To Date</b></label>
        <input type="date" name="to_date" id="to_date"
            class="form-control"
            value="<?= htmlspecialchars($to_date) ?>"
            <?= ($filter_type == 'fy') ? 'readonly' : '' ?>>
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-filter"></i> Apply Filter
        </button>
    </div>

</div>

</form>
<hr style="border:1px solid #dcdcdc; margin:30px 0;">
<!-- 3. KPI CARDS (professional minimal) -->
<?php
// ✅ Set PHP Timezone
date_default_timezone_set('Asia/Kolkata');

// ✅ Force MySQL Timezone to IST
mysqli_query($conn, "SET time_zone = '+05:30'");

$total_trans   = 0;
$total_uses    = 0;
$total_paid    = 0;
$total_free    = 0;
$total_revenue = 0;

// ✅ Overall data till current time (IST)
$sql = "
SELECT 
    COUNT(id) as total_trans,

    COUNT(CASE 
            WHEN status = 'success' 
         THEN 1 END) as total_uses,

    COUNT(CASE 
            WHEN trans_mode IN ('upi','coin') 
            AND status = 'success' 
         THEN 1 END) as paid_count,

    COUNT(CASE 
            WHEN trans_mode = 'button' 
            AND status = 'success' 
         THEN 1 END) as free_count,

    SUM(
        CASE 
            WHEN trans_mode IN ('upi','coin') 
            AND status = 'success'
            THEN trans_amt 
            ELSE 0 
        END
    ) as revenue

FROM trans
WHERE date_time <= NOW()
";

$res = mysqli_query($conn, $sql);

if($row = mysqli_fetch_assoc($res)){
    $total_trans   = $row['total_trans'] ?? 0;
    $total_uses    = $row['total_uses'] ?? 0;
    $total_paid    = $row['paid_count'] ?? 0;
    $total_free    = $row['free_count'] ?? 0;
    $total_revenue = $row['revenue'] ?? 0;
}

// ✅ Overall counts from machines table
$total_clients  = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(DISTINCT client_name) FROM clients"
))[0] ?? 0;

$total_projects = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(DISTINCT project_name) FROM projects"
))[0] ?? 0;

$total_machines = mysqli_fetch_row(mysqli_query($conn,
    "SELECT COUNT(machine_id) FROM machines"
))[0] ?? 0;
?>
    <div class="kpi-grid">

    <div class="kpi-card">
        <div class="kpi-label">Transactions</div>
        <div class="kpi-value"><?= number_format($total_trans) ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Total Uses</div>
        <div class="kpi-value"><?= number_format($total_uses) ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Revenue (₹)</div>
        <div class="kpi-value">₹<?= number_format($total_revenue,2) ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Free Uses</div>
        <div class="kpi-value"><?= number_format($total_free) ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Total Clients</div>
        <div class="kpi-value"><?= $total_clients ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Total Projects</div>
        <div class="kpi-value"><?= $total_projects ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Total Machines</div>
        <div class="kpi-value"><?= $total_machines ?></div>
    </div>

</div>

<?php
/* CHECK IF FILTER APPLIED */
$filterApplied = !empty($selectedClient) || 
                 !empty($selectedProject) || 
                 !empty($selectedMachine) || 
                 !empty($selectedYear) || 
                 !empty($from_date) || 
                 !empty($to_date);
?>

<?php if($_SERVER['REQUEST_METHOD']=="POST" && $filterApplied){ ?>

<hr style="border:1px solid #dcdcdc; margin:30px 0;">

<div class="text-left mb-4">
<h4 style="font-weight:700; color:#2c3e50;">
<i class="fas fa-microchip" style="color:#0d6efd; margin-right:8px;"></i>
Filtered Machine Report
</h4>
</div>

<div class="table-responsive mt-3">

<table id="userTable" class="table table-bordered">
<thead style=''>
<tr>
<th>Machine ID</th>
<th>Client</th>
<th>Project</th>
<th>Entry Fee</th>
<th>Total Uses</th>
<th>UPI</th>
<th>Coin</th>
<th>Free</th>
<th>Revenue</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php

$where = "WHERE 1";

if(!empty($selectedClient)){
$selectedClient = mysqli_real_escape_string($conn,$selectedClient);
$where .= " AND m.client_name='$selectedClient'";
}

if(!empty($selectedProject)){
$selectedProject = mysqli_real_escape_string($conn,$selectedProject);
$where .= " AND m.project_name='$selectedProject'";
}

if(!empty($selectedMachine)){
$selectedMachine = mysqli_real_escape_string($conn,$selectedMachine);
$where .= " AND m.machine_id='$selectedMachine'";
}

if(!empty($selectedYear) || (!empty($from_date) && !empty($to_date))){

$sql = "
SELECT 
m.machine_id,
m.client_name,
m.project_name,
m.uses_amt,

COUNT(t.id) total_uses,
COUNT(CASE WHEN t.trans_mode='upi' THEN 1 END) upi_count,
COUNT(CASE WHEN t.trans_mode='coin' THEN 1 END) coin_count,
COUNT(CASE WHEN t.trans_mode='button' THEN 1 END) free_count,

SUM(CASE 
WHEN t.trans_mode IN('upi','coin')
THEN t.trans_amt ELSE 0 END) revenue

FROM machines m

LEFT JOIN trans t 
ON m.machine_id = t.machin_id
AND t.status='success'
AND t.date_time >= '$from_date 00:00:00' AND t.date_time <= '$to_date 23:59:59'

$where

GROUP BY m.machine_id
ORDER BY m.machine_id
";
}
else{

$sql = "
SELECT 
m.machine_id,
m.client_name,
m.project_name,
m.uses_amt,

COUNT(t.id) total_uses,
COUNT(CASE WHEN t.trans_mode='upi' THEN 1 END) upi_count,
COUNT(CASE WHEN t.trans_mode='coin' THEN 1 END) coin_count,
COUNT(CASE WHEN t.trans_mode='button' THEN 1 END) free_count,

SUM(CASE 
WHEN t.trans_mode IN('upi','coin')
THEN t.trans_amt ELSE 0 END) revenue

FROM machines m

LEFT JOIN trans t 
ON m.machine_id = t.machin_id
AND t.status='success'

$where

GROUP BY m.machine_id
ORDER BY m.machine_id
";
}

$res = mysqli_query($conn,$sql);

if(mysqli_num_rows($res)>0){

while($row=mysqli_fetch_assoc($res)){

echo "<tr>

<td>{$row['machine_id']}</td>
<td>{$row['client_name']}</td>
<td>{$row['project_name']}</td>
<td>₹{$row['uses_amt']}</td>
<td>{$row['total_uses']}</td>
<td>{$row['upi_count']}</td>
<td>{$row['coin_count']}</td>
<td>{$row['free_count']}</td>
<td>₹".number_format($row['revenue'] ?? 0,2)."</td>
<td style='text-align:center;'>

<div style='display:flex; gap:5px; justify-content:center;'>

<!-- Graph Button -->
<form action='chart_user.php' method='POST' style='margin:0;'>

<input type='hidden' name='machine_id' value='{$row['machine_id']}'>

<input type='hidden' name='year' value='{$selectedYear}'>

<input type='hidden' name='from_date' value='{$from_date}'>

<input type='hidden' name='to_date' value='{$to_date}'>

<button type='submit' class='btn btn-sm btn-primary' title='View Graph'>
<i class='fas fa-chart-line'></i>
</button>

</form>

<!-- Transaction Details Button -->
<form action='report_transmachineidwiseuser.php' method='POST' style='margin:0;'>

    <input type='hidden' name='machine_id' value='{$row['machine_id']}'>
    <input type='hidden' name='year' value='{$selectedYear}'>
    <input type='hidden' name='from_date' value='{$from_date}'>
    <input type='hidden' name='to_date' value='{$to_date}'>

    <button type='submit' class='btn btn-sm btn-success' title='Transaction Details'>
        <i class='fas fa-file-invoice'></i>
    </button>

</form>

<!-- View Modal Button -->
<a href='#'
   class='btn btn-sm btn-info view-employee'
   data-toggle='modal'
   data-target='#employeeModal'
   data-machine_id='{$row['machine_id']}'
   title='View'>
   <i class='fas fa-eye'></i>
</a>

</div>

</td>
</tr>";

}

}

?>

</tbody>
</table>
</div>

<?php } else { // Show Detailed Machine Report only when no filter is applied ?>






<hr style="border:1px solid #dcdcdc; margin:30px 0;">

<div class="text-left mb-4">
<h4 style="font-weight:700; color:#2c3e50;">
<i class="fas fa-microchip" style="color:#0d6efd; margin-right:8px;"></i>
Detailed Machine Report
</h4>
</div>

<div class="table-responsive mt-3">

<table id="userTable1" class="table table-bordered">

<thead style="">
<tr>
<th>Machine ID</th>
<th>Client</th>
<th>Project</th>
<th>Entry Fee</th>
<th>Total Uses</th>
<th>UPI</th>
<th>Coin</th>
<th>Free</th>
<th>Revenue</th>
</tr>
</thead>

<tbody>

<?php

$sql2 = "

SELECT 
m.machine_id,
m.client_name,
m.project_name,
m.uses_amt,

COUNT(t.id) AS total_uses,

COUNT(CASE WHEN t.trans_mode='upi' THEN 1 END) AS upi_count,
COUNT(CASE WHEN t.trans_mode='coin' THEN 1 END) AS coin_count,
COUNT(CASE WHEN t.trans_mode='button' THEN 1 END) AS free_count,

SUM(
CASE 
WHEN t.trans_mode IN('upi','coin')
THEN t.trans_amt
ELSE 0
END
) AS revenue

FROM machines m

LEFT JOIN trans t
ON m.machine_id = t.machin_id
AND t.status = 'success'
AND t.date_time <= NOW()

GROUP BY m.machine_id
ORDER BY m.machine_id

";

$res2 = mysqli_query($conn,$sql2);

if(mysqli_num_rows($res2) > 0){

while($row = mysqli_fetch_assoc($res2)){

echo "<tr>

<td>{$row['machine_id']}</td>
<td>{$row['client_name']}</td>
<td>{$row['project_name']}</td>
<td>₹{$row['uses_amt']}</td>
<td>{$row['total_uses']}</td>
<td>{$row['upi_count']}</td>
<td>{$row['coin_count']}</td>
<td>{$row['free_count']}</td>
<td>₹".number_format($row['revenue'] ?? 0,2)."</td>

</tr>";

}

}

?>

</tbody>
</table>

</div>
<?php } // End of else condition ?>
<!-- Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Machines Details</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <div id="employeeDetails"></div>
            </div>

        </div>
    </div>
</div>
</div>
</div>

</div>

<?php include('include/scripts.php'); ?>
<?php include('include/footer.php'); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
$(document).ready(function() {

    $(document).on("click", ".view-employee", function () {
        let machine_id = $(this).data("machine_id");
        $.get("view_machinedetails.php", { machine_id: machine_id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

});

function toggleFilter() {
    let filterType = document.querySelector('input[name="filter_type"]:checked').value;
    let fySection = document.getElementById("fy_section");
    let fromDate = document.getElementById("from_date");
    let toDate = document.getElementById("to_date");
    let fySelect = document.querySelector('select[name="Year_select"]');

    if (filterType === "fy") {
        // Show Financial Year section
        fySection.style.display = "block";
        
        // Make dates readonly
        fromDate.readOnly = true;
        toDate.readOnly = true;
        
        // Set FY to current if it's blank
        if (!fySelect.value) {
            fySelect.value = 'current';
        }
        
        // DON'T auto-submit here to prevent infinite loop
    } else {
        // Hide Financial Year section
        fySection.style.display = "none";
        
        // Enable date inputs
        fromDate.readOnly = false;
        toDate.readOnly = false;
        
        // Clear FY selection when switching to date range
        fySelect.value = '';
        
        // DON'T auto-submit here
    }
}
// Run on page load
document.addEventListener("DOMContentLoaded", function() {
    toggleFilter();
});

function submitForm() {
    // Only submit if there's a valid reason (like manual button click)
    // This function is called from onchange events
    document.getElementById("complaintForm").submit();
}

// Add this to prevent auto-submit on page load
let isFirstLoad = true;

// Modify the toggleFilter function to handle initial load differently
document.addEventListener("DOMContentLoaded", function() {
    // Set up the UI without submitting
    let filterType = document.querySelector('input[name="filter_type"]:checked').value;
    let fySection = document.getElementById("fy_section");
    let fromDate = document.getElementById("from_date");
    let toDate = document.getElementById("to_date");
    let fySelect = document.querySelector('select[name="Year_select"]');

    if (filterType === "fy") {
        fySection.style.display = "block";
        fromDate.readOnly = true;
        toDate.readOnly = true;
        // Ensure FY select has a value (default to current if blank)
        if (!fySelect.value) {
            fySelect.value = 'current';
        }
    } else {
        fySection.style.display = "none";
        fromDate.readOnly = false;
        toDate.readOnly = false;
        // Clear FY selection when Date Range is active
        fySelect.value = '';
    }
    
    isFirstLoad = false;
});

// Optional: Add validation to ensure dates are provided for date range
function validateForm() {
    let filterType = document.querySelector('input[name="filter_type"]:checked').value;
    let fromDate = document.getElementById("from_date").value;
    let toDate = document.getElementById("to_date").value;
    
    if (filterType === "date") {
        if (!fromDate || !toDate) {
            alert("Please select both From Date and To Date for date range filter");
            return false;
        }
        if (fromDate > toDate) {
            alert("From Date cannot be greater than To Date");
            return false;
        }
    }
    return true;
}

// Attach validation to form submission
document.addEventListener("DOMContentLoaded", function() {
    let form = document.getElementById("complaintForm");
    form.onsubmit = function() {
        return validateForm();
    };
});

/* ================= IMAGE → BASE64 ================= */
function getBase64FromImageUrl(url, callback) {
    const img = new Image();
    img.crossOrigin = "anonymous";

    img.onload = function () {
        const canvas = document.createElement("canvas");
        canvas.width = this.width;
        canvas.height = this.height;
        canvas.getContext("2d").drawImage(this, 0, 0);
        callback(canvas.toDataURL("image/png"));
    };
    img.src = url;
}
</script>
<script>

/* ================= PHP VALUES ================= */
const CLIENT_NAME = "<?= !empty($clientName) ? addslashes($clientName) : '' ?>";
const CLIENT_LOGO = "<?= !empty($clientLogo) ? addslashes($clientLogo) : '' ?>";

/* ================= GLOBALS ================= */
let CLIENT_LOGO_BASE64 = '';
let COMPANY_LOGO_BASE64 = '';

$(document).ready(function () {

const now = new Date();

const timestamp = new Intl.DateTimeFormat('en-IN', {
    timeZone: 'Asia/Kolkata',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
})
.format(now)
.replace(/[\/,: ]/g, '_');

const fileName = `Transaction Report(Clientwise)-${timestamp}`;

const companyLogoPath = 'smart-buddy logo.jpeg';

let pending = 1;

if (CLIENT_LOGO) pending++;

function checkInit() {
    pending--;
    if (pending === 0) initTable();
}

/* ================= LOAD LOGOS ================= */

getBase64FromImageUrl(companyLogoPath, function (b64) {
    COMPANY_LOGO_BASE64 = b64;
    checkInit();
});

if (CLIENT_LOGO) {
    getBase64FromImageUrl(CLIENT_LOGO, function (b64) {
        CLIENT_LOGO_BASE64 = b64;
        checkInit();
    });
}

/* ================= DATATABLE ================= */

function initTable() {

$('#userTable, #userTable1').DataTable({

dom:
"<'row mb-2'<'col-md-12 d-flex justify-content-end'B>>" +
"<'row'<'col-md-6'l><'col-md-6'f>>" +
"<'row'<'col-md-12'tr>>" +
"<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

buttons: [

/* ================= EXCEL ================= */

{
extend: 'excelHtml5',
filename: fileName,
title: null,
className: 'dt-btn-custom',

customize: function (xlsx) {

const sheet = xlsx.xl.worksheets['sheet1.xml'];
const sheetData = $('sheetData', sheet);

/* SHIFT ROWS */

sheetData.find('row').each(function () {

const r = parseInt($(this).attr('r'));
$(this).attr('r', r + 2);

$(this).find('c').each(function () {

const cellRef = $(this).attr('r');
const col = cellRef.replace(/[0-9]/g, '');
const row = parseInt(cellRef.replace(/[A-Z]/g, '')) + 2;

$(this).attr('r', col + row);

});

});

/* HEADER */

const headerRows = `
<row r="1">
<c r="A1" t="inlineStr">
<is><t>${now.toLocaleString('en-IN')}</t></is>
</c>
</row>

<row r="2">
<c r="A2" t="inlineStr" s="51">
<is><t>SMART TOILET</t></is>
</c>
</row>
`;

sheetData.prepend(headerRows);

/* MERGE */

let mergeCells = $('mergeCells', sheet);

if (mergeCells.length === 0) {

mergeCells = $('<mergeCells count="0"/>');
$('worksheet', sheet).append(mergeCells);

}

mergeCells.append('<mergeCell ref="A2:C2"/>');
mergeCells.attr('count', mergeCells.find('mergeCell').length);

/* FOOTER */

const footerRow = `
<row>
<c t="inlineStr">
<is>
<t>AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551 - Nashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003. - Mumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012. - Factory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010. - +91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com</t>
</is>
</c>
</row>
`;

sheetData.append(footerRow);

}
},

            {
    extend: 'pdfHtml5',
    filename: fileName,
    orientation: 'landscape',
    pageSize: 'A4',
    title: '',
    className: 'dt-btn-custom',
    exportOptions: { columns: ':visible' },
    customize: function (doc) {
        let imgBase64 = typeof logoBase64 !== 'undefined' ? logoBase64 : (typeof COMPANY_LOGO_BASE64 !== 'undefined' ? COMPANY_LOGO_BASE64 : '');
        let titleText = `Transaction Report(Clientwise)-${timestamp}`;
        
        let headerCols = [
            {
                width: '30%',
                text: [
                    { text: 'Generated By: ', bold: true },
                    { text: '<?php echo isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : (isset($_SESSION["client_mobile"]) ? $_SESSION["client_mobile"] : "User"); ?>\n' },
                    { text: 'Date: ', bold: true },
                    { text: typeof now !== 'undefined' ? now.toLocaleString('en-IN') : new Date().toLocaleString('en-IN') }
                ],
                fontSize: 9,
                alignment: 'left'
            },
            {
                width: imgBase64 ? '40%' : '70%',
                stack: [
                    { text: 'SMART TOILET', alignment: 'center', fontSize: 16, bold: true },
                    { text: titleText, alignment: 'center', fontSize: 12, bold: true }
                ]
            }
        ];
        
        if (imgBase64) {
            headerCols.push({
                width: '30%',
                image: imgBase64,
                fit: [60, 60],
                alignment: 'right'
            });
        }
        
        doc.content.unshift({
            columns: headerCols,
            margin: [0, 0, 0, 10]
        });
        // SPACIOUS & High Contrast PDF (8-10 rows per page)
        doc.defaultStyle.color = '#000000';
        doc.defaultStyle.fontSize = 12; // Big font for high readability
        
        if (!doc.styles) doc.styles = {};
        
        doc.styles.tableHeader = {
            fillColor: '#cccccc', 
            color: '#000000',     
            bold: true,
            fontSize: 13, // Larger header
            alignment: 'center'
        };
        doc.styles.tableBodyEven = {
            alignment: 'center',
            color: '#000000'
        };
        doc.styles.tableBodyOdd = {
            alignment: 'center',
            color: '#000000'
        };

        const tableNode = doc.content.find(c => c.table);
        if (tableNode) {
            tableNode.alignment = 'center';
            
            // THICK Solid black borders with LARGE padding for spacious rows
            tableNode.layout = {
                hLineWidth: function(i, node) { return 1.5; },
                vLineWidth: function(i, node) { return 1.5; },
                hLineColor: function(i, node) { return '#000000'; },
                vLineColor: function(i, node) { return '#000000'; },
                paddingLeft: function(i, node) { return 6; },
                paddingRight: function(i, node) { return 6; },
                paddingTop: function(i, node) { return 10; }, // LARGE padding for fewer rows per page
                paddingBottom: function(i, node) { return 10; }
            };

            tableNode.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    cell.color = '#000000';
                });
            });
        }

        doc.footer = function (currentPage, pageCount) {
            return {
                columns: [
                    { width: '*', text: '', alignment: 'left' },
                    {
                        width: 'auto',
                        text: 'AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551\nNashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.\nMumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.\nFactory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.\n+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com',
                        alignment: 'center',
                        fontSize: 8
                    },
                    {
                        width: '*',
                        text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                        alignment: 'right',
                        fontSize: 8,
                        margin: [0, 0, 20, 0]
                    }
                ],
                margin: [20, 0, 20, 10]
            };
        };
    }
},

            {
    extend: 'print',
    title: '',
    className: 'dt-btn-custom',
    exportOptions: { columns: ':visible' },
    customize: function (win) {
        $(win.document.body).css('font-size', '12px');
        $(win.document.body).find('h1').remove();
        
        let genBy = '<?php echo isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : (isset($_SESSION["client_mobile"]) ? $_SESSION["client_mobile"] : "User"); ?>';
        let dateStr = now.toLocaleString('en-IN');
        
        $(win.document.body).prepend(`
            <div style="margin-bottom:15px; border-bottom: 2px solid #000; padding-bottom: 10px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="width:30%; font-size:11px;">
                        <div><b>Generated By:</b> ${genBy}</div>
                        <div><b>Date:</b> ${dateStr}</div>
                    </div>
                    <div style="text-align:center; width:40%;">
                        <div style="font-size:18px; font-weight:bold;">SMART TOILET</div>
                        <div style="font-size:14px; font-weight:bold;">Transaction Report(Clientwise)-${timestamp}</div>
                    </div>
                    <div style="width:30%; text-align:right;">
                        <img src="${typeof logoPath !== 'undefined' ? logoPath : (typeof companyLogoPath !== 'undefined' ? companyLogoPath : '')}" style="height:50px;">
                    </div>
                </div>
            </div>
        `);

        $(win.document.body).append(`
            <style>
            @media print {
                @page { margin-bottom: 45mm; }
                .dt-print-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: auto;
                    border-top: 1px solid #000;
                    font-size: 10px;
                    line-height: 14px;
                    padding: 5px 10px;
                    
                    text-align: center;
                }
            }
            </style>
            <div class="dt-print-footer">
                <div><strong>AARYA INNOVTECH PVT. LTD.</strong> CIN : U29305MH2019PTC327551<br><strong>Nashik Office :</strong> Flat No.4A, Sayali Darshan -A-Wing, Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.<br><strong>Mumbai Office :</strong> Flat No.C-03, The Maharashtra Chs Ltd, C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.<br><strong>Factory :</strong> S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.<br>+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com</div>
            </div>
        `);
    }
}

            ]

});

}

});

</script>
