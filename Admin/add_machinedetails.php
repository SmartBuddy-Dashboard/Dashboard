<?php 
require_once('include/header.php');

    require_once('include/navbar.php');
    require_once('include/config.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $machine_id        = mysqli_real_escape_string($conn, $_POST['machine_id']);
    $client_name       = mysqli_real_escape_string($conn, $_POST['client_name']);
    $state             = mysqli_real_escape_string($conn, $_POST['state']);
    $district          = mysqli_real_escape_string($conn, $_POST['district']);
    $city              = mysqli_real_escape_string($conn, $_POST['city']);
    $address           = mysqli_real_escape_string($conn, $_POST['address']);
    $inst_address           = mysqli_real_escape_string($conn, $_POST['inst_address']);
    $uses_amt          = mysqli_real_escape_string($conn, $_POST['uses_amt']);
    $status            = mysqli_real_escape_string($conn, $_POST['status']);
    $project_name      = mysqli_real_escape_string($conn, $_POST['project_name']);
    $po_date           = mysqli_real_escape_string($conn, $_POST['po_date']);
   $installation_date = !empty($_POST['installation_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['installation_date']) . "'" : "NULL";
$dispatch_date     = !empty($_POST['dispatch_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['dispatch_date']) . "'" : "NULL";

    $wall_clean        = mysqli_real_escape_string($conn, $_POST['wall_clean']);
    $seats      = !empty($_POST['seats']) ? mysqli_real_escape_string($conn, $_POST['seats']) : "NULL";
    $flush_time        = mysqli_real_escape_string($conn, $_POST['flush_time']);
    $floor_time        = mysqli_real_escape_string($conn, $_POST['floor_time']);
    $wall_time  = !empty($_POST['wall_time']) ? mysqli_real_escape_string($conn, $_POST['wall_time']) : "NULL";

    // Checkbox values
    $free          = isset($_POST['Button']) ? 'Yes' : 'No';
    $coin          = isset($_POST['coin']) ? 'Yes' : 'No';
    $upi           = isset($_POST['upi']) ? 'Yes' : 'No';
    $smart_card    = isset($_POST['smart_card']) ? 'Yes' : 'No';
    $digital_token = isset($_POST['digital_token']) ? 'Yes' : 'No';



    // Insert into machines table
   $insertQuery = "INSERT INTO machines 
(
    machine_id, client_name, state, district, city, address,
    uses_amt, status, project_name, po_date, installation_date,
    wall_clean, seats, flush_time, floor_time, wall_time,
    free, coin, upi, smart_card, digital_token, dispatch_date,inst_address
)
VALUES
(
    '$machine_id', '$client_name', '$state', '$district', '$city', '$address',
    '$uses_amt', '$status', '$project_name', '$po_date', $installation_date,
    '$wall_clean', $seats, '$flush_time', '$floor_time', $wall_time,
    '$free', '$coin', '$upi', '$smart_card', '$digital_token', $dispatch_date, '$inst_address'
)";


    if (mysqli_query($conn, $insertQuery)) {

        // --------------------------------------
        // INSERT INTO machine_logs TABLE
        // --------------------------------------
        $logQuery = "
            INSERT INTO machine_logs (machine_id, status, start_time)
            VALUES ('$machine_id', '$status', NOW())
        ";
        mysqli_query($conn, $logQuery);

        echo "<script>alert('Machine added successfully'); window.location='list_machinedetails.php';</script>";
        exit;

    } else {
        echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
    }
}
 ?>
 <head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error { color: red; font-size: 0.9rem; }
    input[readonly], textarea[readonly] {
     background-color: #f1f1f1 !important;  /* Light Gray */
    color: #000 !important;              /* Normal dark text */
    opacity: 1 !important;               /* Remove faded effect */
    cursor: default;
}
.entry-fee-group .input-group-text {
    padding-right: 2px;   /* remove space after ₹ */
}

.entry-fee-group .form-control {
    padding-left: 4px;    /* tighten input spacing */
}
  </style>

 </head>

    <div id="content-wrapper" class="d-flex flex-column">
       <div id="content">

            <div class="container-fluid">
                <div class="card shadow mb-4">
                 <div class="card-header py-3">
                        <h5 class="text-center"><b>Add Machine Details</b></h5>
                        
                    </div>   
                  
    <div class="card-body">

 <form id="myForm" method="POST" enctype="multipart/form-data">
          <div class="row">

            <div class="col-md-4 mb-3">
              <label>Machine Id</label>
              <input type="text" name="machine_id" id="machine_id" placeholder="Enter Machine name" maxlength="20" class="form-control" required>
               <small id="msg" style="color:red;"></small>
            </div>

            <div class="col-md-4 mb-3">
              <label>Client Name</label>
              
              <select class="form-control" name="client_name" id="client_name" required>
                <option value="">-- Select Client Name --</option>
                <?php
                $stateQuery = "SELECT DISTINCT client_name,client_state,clinet_district,client_city,client_address FROM clients ";
                $stateResult = mysqli_query($conn, $stateQuery);
                while ($row = mysqli_fetch_assoc($stateResult)) {
                    echo '<option 
                value="' . htmlspecialchars($row['client_name']) . '" 
                data-state="' . htmlspecialchars($row['client_state']) . '" 
                data-district="' . htmlspecialchars($row['clinet_district']) . '" 
                data-city="' . htmlspecialchars($row['client_city']) . '" 
                data-address="' . htmlspecialchars($row['client_address']) . '"
            >' 
            . htmlspecialchars($row['client_name']) . 
            '</option>';
                }
                ?>
              </select>
            </div>
          

            <div class="col-md-4 mb-3">
              <label>State</label>
             <input type="text" name="state" id="state" maxlength="30" placeholder="Enter State" class="form-control" readonly required>
            </div>

            <div class="col-md-4 mb-3">
              <label>District</label>
              <input type="text" name="district" id="district" placeholder="Enter District" maxlength="50" class="form-control" readonly required>

              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label>City</label>
             <input type="text" name="city" id="city" placeholder="Enter City" maxlength="30" class="form-control" readonly required>

              </select>
            </div>

            <div class="col-md-4 mb-3">
  <label>Address</label> 
  <textarea name="address" id="address" placeholder="Enter Address" maxlength="200" class="form-control" rows="1" required></textarea>
</div>


<!-- New Project Name Field -->
    <div class="col-md-4 mb-3">
      <label>Sale Order No.</label>
     
       <select class="form-control" name="project_name" id="project_name" required>
    <option value="">-- Select Sale Order --</option>
</select>

              </select>
    </div>

    <!-- New PO Date Field -->
    <div class="col-md-4 mb-3">
      <label>Project Date</label>
      <input type="date" name="po_date" id="po_date" readonly class="form-control" required>
    </div>
     <!-- New Installation Date Field -->
    <div class="col-md-4 mb-3">
      <label>Installation Date</label>
      <input type="date" name="installation_date" id="installation_date" class="form-control">
    </div>
     <div class="col-md-4 mb-3">
  <label>Machine Installation Address</label> 
  <textarea name="inst_address" id="inst_address" placeholder="Enter Address" maxlength="200" class="form-control" rows="1"></textarea>
</div>
    <div class="col-md-4 mb-3">
      <label>Dispatch Date</label>
      <input type="date" name="dispatch_date" id="dispatch_date" class="form-control">
    </div>


   <div class="col-md-4 mb-3">
    <label>Entry Fee</label>

    <div class="input-group entry-fee-group">
        <span class="input-group-text">₹</span>
        <input type="text"
               name="uses_amt"
               id="uses_amt"
               value="4"
               maxlength="3"
               class="form-control"
               required
               oninput="this.value = this.value.replace(/\D/g,'').slice(0,3);">
    </div>
</div>




   



          

            <!-- New Status Field -->
           <div class="col-md-4 mb-3">
  <label>Machine Mode</label>
  <select name="status" class="form-control" required>
    <option value="">-- Select Status --</option>
    <option value="ready" selected>Ready</option>
    <option value="maintenance">Maintenance</option>
    
  </select>
</div>


<!---------------- Wall Clean ------------------>
<div class="col-md-4 mb-3">
  <label>Wall Cleaning Mode</label>
  <select name="wall_clean" id="wall_clean" class="form-control" onchange="toggleFields()" required>
      <option value="En" selected>Enable</option>
      <option value="Dis">Disable</option>
  </select>
</div>



  <div class="col-md-4 mb-3">
    <label>Wall Cleaning After Users</label>
    <input type="text"
           name="seats"
           id="seats"
           value="5 users"
           class="form-control"
           onfocus="removeUsers(this)"
           onblur="addUsers(this)"
           oninput="onlyNumber(this)">
</div>

<div class="col-md-4 mb-3">
    <label>Flush Time</label>
    <input type="text"
           name="flush_time"
           id="flush_time"
           value="5 sec"
           class="form-control"
           onfocus="removeUnit(this)"
           onblur="addSec(this)"
           oninput="onlyNumber(this)">
</div>

<div class="col-md-4 mb-3">
    <label>Floor Cleaning Time</label>
    <input type="text"
           name="floor_time"
           id="floor_time"
           value="11 sec"
           class="form-control"
           onfocus="removeUnit(this)"
           onblur="addSec(this)"
           oninput="onlyNumber(this)">
</div>

<div class="col-md-4 mb-3">
    <label>Wall Cleaning Time</label>
    <input type="text"
           name="wall_time"
           id="wall_time"
           value="11 sec"
           class="form-control"
           onfocus="removeUnit(this)"
           onblur="addSec(this)"
           oninput="onlyNumber(this)">
</div>


<div class="col-md-8 mb-3" id="paymentModes">
  <label><strong>Access Mode</strong></label><br>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="Button" id="Button" value="Yes">
    <label class="form-check-label" for="Button">Button</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="coin" id="coin" value="Yes">
    <label class="form-check-label" for="coin">Coin</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="upi" id="upi" value="Yes">
    <label class="form-check-label" for="upi">UPI</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="smart_card" id="smart_card" value="Yes">
    <label class="form-check-label" for="smart_card">Smart Card</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="digital_token" id="digital_token" value="Yes">
    <label class="form-check-label" for="digital_token">Digital Token</label>
  </div>
</div>


            </div>




            <!-- New Amount Field -->
           
          
          <div class="text-center mt-4">
            <button type="submit" id="submitBtn" class="btn btn-success">Submit</button>
            <a href="list_machinedetails.php" class="btn btn-secondary ms-2">Back</a>
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
// ===== ONLY NUMBERS =====
function onlyNumber(input) {
    input.value = input.value.replace(/\D/g, '');
}

// ===== USERS =====
function removeUsers(input) {
    input.value = input.value.replace(' users', '');
}

function addUsers(input) {
    if (input.value !== '' && !input.value.includes('users')) {
        input.value = input.value + ' users';
    }
}

// ===== SECONDS =====
function removeUnit(input) {
    input.value = input.value.replace(' sec', '');
}

function addSec(input) {
    if (input.value !== '' && !input.value.includes('sec')) {
        input.value = input.value + ' sec';
    }
}

 $(document).ready(function(){
    $("#machine_id").on("blur", function(){
        var machineId = $(this).val();

        if(machineId !== ""){
            $.ajax({
                url: "check_machine.php",
                type: "POST",
                data: { machine_id: machineId },
                success: function(response){
                    if(response == "exists"){
                        $("#msg").text("⚠️ Machine ID already exists!");
                        $("#machine_id").val("").css("border", "2px solid red").focus();
                    } else {
                        $("#msg").text("");
                        $("#machine_id").css("border", "2px solid green");
                    }
                }
            });
        }
    });
});


  function toggleFields() {
    let wallClean = document.getElementById("wall_clean").value;
    let seats = document.getElementById("seats");
    let wallTime = document.getElementById("wall_time");

    if (wallClean === "Dis") {
        seats.value = "";
        wallTime.value = "";
        seats.setAttribute("readonly", true);
        wallTime.setAttribute("readonly", true);
    } else {
        seats.removeAttribute("readonly");
        wallTime.removeAttribute("readonly");

        if (!seats.value) seats.value = "5";
        if (!wallTime.value) wallTime.value = "11";
    }
}

// Run once when page loads (edit mode support)
document.addEventListener("DOMContentLoaded", toggleFields);


  const form = document.getElementById('myForm');
const submitBtn = document.getElementById('submitBtn');


form.addEventListener('submit', function(e) {
    // Strip units before sending
    const seats = document.getElementById('seats');
    const flush = document.getElementById('flush_time');
    const floor = document.getElementById('floor_time');
    const wall  = document.getElementById('wall_time');

    seats.value = seats.value.replace(/\D/g, '');
    flush.value = flush.value.replace(/\D/g, '');
    floor.value = floor.value.replace(/\D/g, '');
    wall.value  = wall.value.replace(/\D/g, '');

    // Existing payment mode validation
    if (!validatePaymentModes()) {
        e.preventDefault();
        submitBtn.disabled = false;
        submitBtn.innerText = 'Submit';
        return false;
    }

    submitBtn.disabled = true;
    submitBtn.innerText = 'Submitting...';
});

  document.getElementById('client_name').addEventListener('change', function () {

    var selected = this.options[this.selectedIndex];

    document.getElementById('state').value    = selected.getAttribute('data-state') || '';
    document.getElementById('district').value = selected.getAttribute('data-district') || '';
    document.getElementById('city').value     = selected.getAttribute('data-city') || '';
    document.getElementById('address').value  = selected.getAttribute('data-address') || '';
});




document.addEventListener("DOMContentLoaded", function () {

    const button = document.getElementById('Button'); // FREE / Button checkbox
    const amount = document.getElementById('uses_amt');

    const others = ['coin','upi','smart_card','digital_token']
        .map(id => document.getElementById(id));

    // When Button (FREE) is clicked
    button.addEventListener('change', function () {

        if (this.checked) {

            // Disable & uncheck others
            others.forEach(el => {
                el.checked = false;
                el.disabled = true;
            });

            // Set default amount
            amount.value = 5;
            amount.readOnly = true;

        } else {

            // Enable others
            others.forEach(el => el.disabled = false);

            // Reset amount only if no other option selected
            const anyChecked = others.some(el => el.checked);
            amount.readOnly = false;
            if (!anyChecked) amount.value = "";
        }
    });

    // When other payment mode is selected
    others.forEach(el => {
        el.addEventListener('change', function () {

            const anyChecked = others.some(o => o.checked);

            // Disable Button if any other mode is selected
            button.disabled = anyChecked;

            if (anyChecked) {
                button.checked = false;
                amount.readOnly = false;

                // Clear default value if coming from Button
                if (amount.value == 5) amount.value = "";
            } 
            else {
                button.disabled = false;
                if (!button.checked) amount.value = "";
            }
        });
    });

});

  function validatePaymentModes() {
    const checkboxes = document.querySelectorAll("#paymentModes input[type='checkbox']");
    let checked = false;

    checkboxes.forEach(cb => {
        if (cb.checked) checked = true;
    });

    if (!checked) {
        alert("Please select at least one payment mode.");
        return false;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", function () {

    const projectSelect = document.getElementById("project_name");
    const projectDate = document.getElementById("po_date");
    const installationDate = document.getElementById("installation_date");

    // When project name changes → set project date
    projectSelect.addEventListener("change", function () {
        let selectedOption = this.options[this.selectedIndex];
        let projectStartDate = selectedOption.getAttribute("data-projectdate");

        projectDate.value = projectStartDate ? projectStartDate : "";

        // If installation date exists and is earlier, reset it
        if (installationDate.value && new Date(installationDate.value) < new Date(projectDate.value)) {
            alert("Installation Date cannot be earlier than Project Date.");
            installationDate.value = "";
            installationDate.focus();
        }
    });

    // When installation date changes → validate
    installationDate.addEventListener("change", function () {
        if (!projectDate.value) return;  // Stop if no project date selected yet

        let poDateValue = new Date(projectDate.value);
        let installDateValue = new Date(this.value);

        if (installDateValue < poDateValue) {
            alert("Installation Date must be equal or greater than Project Date.");
            this.value = "";
            this.focus();
        }
    });

});

document.getElementById('client_name').addEventListener('change', function () {

    let clientName = this.value;
    let projectSelect = document.getElementById('project_name');
    let poDate = document.getElementById('po_date');

    projectSelect.innerHTML = '<option>Loading...</option>';
    poDate.value = '';

    if (clientName === '') {
        projectSelect.innerHTML = '<option value="">-- Select Sale Order --</option>';
        return;
    }

    fetch('fetch_projects.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'client_name=' + encodeURIComponent(clientName)
    })
    .then(res => res.text())
    .then(data => {
        projectSelect.innerHTML = data; // ✅ default option stays
        poDate.value = '';              // ✅ clear po_date
    });
});

// Update po_date only when user selects sale order
document.getElementById('project_name').addEventListener('change', function () {
    let selected = this.options[this.selectedIndex];
    let projectDate = selected.getAttribute('data-projectdate');
    document.getElementById('po_date').value = projectDate ? projectDate : '';
});


 </script>



