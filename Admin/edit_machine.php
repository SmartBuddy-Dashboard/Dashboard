<?php
include('include/config.php');

$id = $_GET['updateid'];
$query = "
SELECT 
    m.*,
    p.sale_ord_no,
    p.project_starts
FROM machines m
LEFT JOIN projects p 
    ON m.project_name = p.project_name
WHERE m.id = '$id'
";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $machine_id   = $row["machine_id"];
    $client_name  = $row["client_name"];
    $state        = $row["state"];
    $district     = $row["district"];
    $city         = $row["city"];
    $address      = $row["address"];
    $inst_address      = $row["inst_address"];
    $uses_amt     = $row["uses_amt"];
    $status       = $row["status"];
    $project_name = $row["project_name"];
    $po_date      = $row["po_date"];
    $installation_date = $row["installation_date"];
    $dispatch_date = $row["dispatch_date"];
    $wall_clean   = $row['wall_clean'];
    $seats        = $row['seats'];
    $flush_time   = $row['flush_time'];
    $floor_time   = $row['floor_time'];
    $wall_time    = $row['wall_time'];

    // Checkbox values
    $free          = $row['free'];
    $coin          = $row['coin'];
    $upi           = $row['upi'];
    $smart_card    = $row['smart_card'];
    $digital_token = $row['digital_token'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <title>Update Machine Details</title>
  <style>.entry-fee-group .input-group-text {
    padding-right: 2px;   /* remove space after ₹ */
}

.entry-fee-group .form-control {
    padding-left: 4px;    /* tighten input spacing */
}</style>
</head>
<body>

<div class="container mt-5">
<form action="update_machine.php" method="post" enctype="multipart/form-data" onsubmit="return validatePaymentModes();">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<div class="row">

<!---------------- Machine ID ------------------>
<div class="col-md-4 mb-3">
    <label>Machine ID</label>
    <input type="text" name="machine_id" class="form-control" maxlength="20"
           value="<?php echo htmlspecialchars($machine_id); ?>" readonly required>
</div>

<!---------------- Client Name ------------------>
<div class="col-md-4 mb-3">
  <label>Client Name</label>
<select class="form-control" name="client_name" id="client_name" required>
    <option value="">-- Select Client Name --</option>

    <?php
    $clientQuery = "SELECT DISTINCT client_name, client_state, clinet_district, client_city, client_address FROM clients";
    $clientResult = mysqli_query($conn, $clientQuery);

    while ($clientRow = mysqli_fetch_assoc($clientResult)) {

        $selected_client = ($clientRow['client_name'] === $client_name) ? 'selected' : '';

        echo '<option value="' . htmlspecialchars($clientRow['client_name']) . '" ' . $selected_client . '
                data-state="' . htmlspecialchars($clientRow['client_state']) . '"
                data-district="' . htmlspecialchars($clientRow['clinet_district']) . '"
                data-city="' . htmlspecialchars($clientRow['client_city']) . '"
                data-address="' . htmlspecialchars($clientRow['client_address']) . '"
              >' 
              . htmlspecialchars($clientRow['client_name']) . 
              '</option>';
    }
    ?>

</select>
</div>

<!---------------- State ------------------>
<div class="col-md-4 mb-3">
    <label>State</label>
    <input type="text" name="state" id="state" class="form-control"
           value="<?php echo htmlspecialchars($state); ?>" readonly required>
</div>

<!---------------- District ------------------>
<div class="col-md-4 mb-3">
    <label>District</label>
    <input type="text" name="district" id="district" class="form-control"
           value="<?php echo htmlspecialchars($district); ?>" readonly required>
</div>

<!---------------- City ------------------>
<div class="col-md-4 mb-3">
    <label>City</label>
    <input type="text" name="city" id="city" class="form-control"
           value="<?php echo htmlspecialchars($city); ?>" readonly required>
</div>

<!---------------- Address ------------------>
<div class="col-md-4 mb-3">
    <label>Address</label>
    <textarea name="address" id="address" class="form-control" rows="1" readonly required><?php echo htmlspecialchars($address); ?></textarea>
</div>

<!---------------- Project Name ------------------>
<div class="col-md-4 mb-3">
  <label>Sale Order No.</label>
  <select class="form-control" name="project_name" id="project_name" required>
    <option value="">-- Select Sale Order --</option>

    <?php
    $projectQuery = "
        SELECT project_name, sale_ord_no, project_starts
        FROM projects
        WHERE client_name = '$client_name'
    ";
    $projectResult = mysqli_query($conn, $projectQuery);

    while ($pr = mysqli_fetch_assoc($projectResult)) {

        $selected = ($pr['project_name'] == $project_name) ? 'selected' : '';

        echo '<option value="'.htmlspecialchars($pr['project_name']).'" '.$selected.'
                data-projectdate="'.htmlspecialchars($pr['project_starts']).'">'
             .htmlspecialchars($pr['sale_ord_no']).'
             </option>';
    }
    ?>
  </select>
</div>


<!---------------- Project Date ------------------>
<div class="col-md-4 mb-3">
    <label>Project Date</label>
    <input type="date" name="po_date" id="po_date"
           value="<?php echo htmlspecialchars($po_date); ?>" 
           readonly class="form-control" required>
</div>

<!---------------- Installation Date ------------------>
<div class="col-md-4 mb-3">
    <label>Installation Date</label>
    <input type="date" name="installation_date" id="installation_date"
           value="<?php echo htmlspecialchars($installation_date); ?>" 
           class="form-control" >
</div>
  <div class="col-md-4 mb-3">
  <label>Machine Installation Address</label> 
  <textarea name="inst_address" id="inst_address" placeholder="Enter Address" maxlength="200" class="form-control" rows="1"><?php echo htmlspecialchars($inst_address); ?></textarea>
</div>

<div class="col-md-4 mb-3">
    <label>Dispatch Date</label>
    <input type="date" name="dispatch_date" id="dispatch_date"
           value="<?php echo htmlspecialchars($dispatch_date); ?>" 
           class="form-control" >
</div>


<!---------------- Amount ------------------>
<div class="col-md-4 mb-3">
    <label>Entry Fee</label>
   <div class="input-group entry-fee-group">
        <span class="input-group-text">₹</span>
        <input type="text" name="uses_amt" 
               value="<?php echo htmlspecialchars($uses_amt); ?>" 
               class="form-control" maxlength="3"
               oninput="this.value=this.value.replace(/\D/g,'').slice(0,3);" 
               required>
    </div>
</div>



<!---------------- Status ------------------>
<div class="col-md-4 mb-3">
    <label>Machine Mode</label>
    <select name="status" class="form-control" required>
        <option value="">-- Select Status --</option>
        <option value="ready"          <?php echo ($status=='ready')?'selected':''; ?>>Ready</option>
        <option value="maintenance"    <?php echo ($status=='maintenance')?'selected':''; ?>>Maintenance</option>
        
    </select>
</div>


<div class="col-md-4 mb-3">
  <label>Wall Cleaning Mode</label>
   <select name="wall_clean" id="wall_clean" class="form-control" oninput="handleWallClean();" required>
    <option value="">-- Select Wall Clean --</option>
      <option value="En" <?php if($wall_clean == 'En') echo 'selected'; ?>>Enable</option>
      <option value="Dis" <?php if($wall_clean == 'Dis') echo 'selected'; ?>>Disable</option>
  </select>
</div>


<!---------------- Seats ------------------>
<div class="col-md-4 mb-3">
  <label>Wall Cleaning After Users</label>
  <input type="text"
         name="seats"
         id="seats"
         value="<?php echo htmlspecialchars($seats); ?>"
         class="form-control"
         onfocus="removeUsers(this)"
         onblur="addUsers(this)"
         required>
</div>

<!---------------- Flush Time ------------------>
<div class="col-md-4 mb-3">
  <label>Flush Time</label>
  <input type="text"
         name="flush_time"
         id="flush_time"
         value="<?php echo htmlspecialchars($flush_time); ?>"
         class="form-control"
         onfocus="removeSec(this)"
         onblur="addSec(this)"
         required>
</div>

<!---------------- Floor Time ------------------>
<div class="col-md-4 mb-3">
  <label>Floor Cleaning Time</label>
  <input type="text"
         name="floor_time"
         id="floor_time"
         value="<?php echo htmlspecialchars($floor_time); ?>"
         class="form-control"
         onfocus="removeSec(this)"
         onblur="addSec(this)"
         required>
</div>

<!---------------- Wall Time ------------------>
<div class="col-md-4 mb-3">
  <label>Wall Cleaning Time</label>
  <input type="text"
         name="wall_time"
         id="wall_time"
         value="<?php echo htmlspecialchars($wall_time); ?>"
         class="form-control"
         onfocus="removeSec(this)"
         onblur="addSec(this)"
         required>
</div>



<!---------------- Payment Modes ------------------>
<div class="col-md-8 mb-3" id="paymentModes">
  <label><strong>Access Mode</strong></label><br>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="Button" id="Button" value="Yes" 
           <?php if($free=="Yes") echo "checked"; ?>>
    <label class="form-check-label" for="Button">Button</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input pm" type="checkbox" name="coin" id="coin" value="Yes"
           <?php if($coin=="Yes") echo "checked"; ?>>
    <label class="form-check-label" for="coin">Coin</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input pm" type="checkbox" name="upi" id="upi" value="Yes"
           <?php if($upi=="Yes") echo "checked"; ?>>
    <label class="form-check-label" for="upi">UPI</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input pm" type="checkbox" name="smart_card" id="smart_card" value="Yes"
           <?php if($smart_card=="Yes") echo "checked"; ?>>
    <label class="form-check-label" for="smart_card">Smart Card</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input pm" type="checkbox" name="digital_token" id="digital_token" value="Yes"
           <?php if($digital_token=="Yes") echo "checked"; ?>>
    <label class="form-check-label" for="digital_token">Digital Token</label>
  </div>

</div>

</div>

<div class="text-center">
    <button type="submit" name="update" class="btn btn-primary">Update</button>
</div>

</form>
</div>

<script>
// ===== USERS =====
function removeUsers(input) {
    input.value = input.value.replace(/\s*users$/i, '');
}
function addUsers(input) {
    if (input.value.trim() !== '' && !/users$/i.test(input.value)) {
        input.value = input.value.replace(/\D/g, '') + ' users';
    }
}

// ===== SECONDS =====
function removeSec(input) {
    input.value = input.value.replace(/\s*sec$/i, '');
}
function addSec(input) {
    if (input.value.trim() !== '' && !/sec$/i.test(input.value)) {
        input.value = input.value.replace(/\D/g, '') + ' sec';
    }
}

/* ==================================================
   FORCE ADD UNITS AFTER PAGE + WALL CLEAN LOAD
================================================== */
function applyUnits() {
    const seats = document.getElementById('seats');
    const timeFields = ['flush_time', 'floor_time', 'wall_time'];

    if (seats && seats.value.trim() !== '') {
        addUsers(seats);
    }

    timeFields.forEach(id => {
        const el = document.getElementById(id);
        if (el && el.value.trim() !== '') {
            addSec(el);
        }
    });
}

// DOM ready
document.addEventListener("DOMContentLoaded", function () {
    // delay ensures handleWallClean finishes first
    setTimeout(applyUnits, 300);
});
</script>



<script>



function handleWallClean() {
    let wc = document.getElementById("wall_clean").value.trim();
    let seats = document.getElementById("seats");
    let wallTime = document.getElementById("wall_time");

    if (wc === "Dis") {
        seats.readOnly = true;
        wallTime.readOnly = true;
        seats.value = "";
        wallTime.value = "";

        seats.required = false;
        wallTime.required = false;
    } 
    else if (wc === "En") {
        seats.readOnly = false;
        wallTime.readOnly = false;

        seats.required = true;
        wallTime.required = true;

        if (seats.value.trim() === "" || seats.value === "0") {
            seats.value = "5";
        }

        if (wallTime.value.trim() === "" || wallTime.value === "0") {
            wallTime.value = "11";
        }
    }

    // 🔥 FORCE UNITS AFTER LOGIC
    applyUnits();
}

// 🔥 Delay execution so elements exist
setTimeout(handleWallClean, 300);


/* ---------------- Auto Fill Client Details ---------------- */
document.getElementById('client_name').addEventListener('change', function () {

    let selected = this.options[this.selectedIndex];

    // Fill address fields
    document.getElementById('state').value    = selected.getAttribute('data-state') || '';
    document.getElementById('district').value = selected.getAttribute('data-district') || '';
    document.getElementById('city').value     = selected.getAttribute('data-city') || '';
    document.getElementById('address').value  = selected.getAttribute('data-address') || '';

    // Reset project + date
    let projectSelect = document.getElementById('project_name');
    let poDate = document.getElementById('po_date');

    projectSelect.innerHTML = '<option>Loading...</option>';
    poDate.value = '';

    let clientName = this.value;
    if (clientName === '') {
        projectSelect.innerHTML = '<option value="">-- Select Sale Order --</option>';
        return;
    }

    // Fetch sale orders
    fetch('fetch_projects.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'client_name=' + encodeURIComponent(clientName)
    })
    .then(res => res.text())
    .then(data => {
        projectSelect.innerHTML = data;
    });
});



/* ---------------- Update PO Date on Sale Order Change ---------------- */
document.getElementById('project_name').addEventListener('change', function () {
    let selected = this.options[this.selectedIndex];
    let projectDate = selected.getAttribute('data-projectdate');

    document.getElementById('po_date').value = projectDate ? projectDate : '';
});


function togglePaymentModes() {
    let button = document.getElementById("Button");
    let otherModes = document.querySelectorAll(".pm");
    let amount = document.querySelector("input[name='uses_amt']");

    // If FREE selected → disable and uncheck other payments + lock amount input
    if (button.checked) {
        otherModes.forEach(cb => {
            cb.checked = false;
            cb.disabled = true;
        });

        amount.value = "5";  // Optional: set value to 0
        amount.readOnly = true;
        amount.required = false;
    } 

    // If any paid mode selected → FREE disabled + amount enabled
    else {
        let anyOtherChecked = [...otherModes].some(cb => cb.checked);

        button.disabled = anyOtherChecked;

        otherModes.forEach(cb => cb.disabled = false);

        amount.readOnly = false;
        amount.required = true;

        // If amount is empty after enabling, give default
        if (amount.value.trim() === "" || amount.value === "0") {
            amount.value = "5"; // You can change default if needed
        }
    }
}

// Run on page load
togglePaymentModes();

// Attach events
document.querySelectorAll("#paymentModes input[type='checkbox']").forEach(cb => {
    cb.addEventListener("change", togglePaymentModes);
});


function validatePaymentModes() {
    const checkboxes = document.querySelectorAll("#paymentModes input[type='checkbox']");
    let isChecked = false;

    checkboxes.forEach(cb => {
        if (cb.checked) isChecked = true;
    });

    if (!isChecked) {
        alert("⚠ Please select at least one payment mode.");
        return false;
    }
    return true;
}

/* ---------------- Install Date Validation ---------------- */

function validateDate() {
    const projectDate = document.getElementById("po_date").value;
    const installationDate = document.getElementById("installation_date").value;

    if (!projectDate || !installationDate) return true;

    if (new Date(installationDate) < new Date(projectDate)) {
        alert("❌ Installation Date must be equal or greater than Project Date.");
        document.getElementById("installation_date").value = "";
        document.getElementById("installation_date").focus();
        return false;
    }
    return true;
}

// Trigger validation on date change
document.getElementById("installation_date").addEventListener("change", validateDate);

// Block form submit if validation fails
document.querySelector("form").addEventListener("submit", function(e){
    if (!validateDate() || !validatePaymentModes()) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
