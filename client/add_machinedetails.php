<?php
include('include/config.php');

// -----------------------------
// DIRECT SESSION CHECK
// -----------------------------
if (!isset($_SESSION['mobile'])) {
    echo "<script>alert('User not logged in!'); window.location='index.php';</script>";
    exit();
}

$mobile = $_SESSION['mobile'];

// Optional: check inactivity timeout
$inactive = 900;  // 2 minutes

if (isset($_SESSION['timeout'])) {
    if (time() - $_SESSION['timeout'] > $inactive) {

        // Update login status in DB
        if (isset($_SESSION['user_id'])) {
            $uid = $_SESSION['user_id'];
            mysqli_query($conn, "UPDATE tblusers SET is_logged_in = 0 WHERE id = '$uid'");
        }

        session_unset();
        session_destroy();

        echo "<script>alert('Session expired!'); window.location='index.php';</script>";
        exit();
    }
}

$_SESSION['timeout'] = time();
// -----------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $machine_id        = mysqli_real_escape_string($conn, $_POST['machine_id']);
    $client_name       = mysqli_real_escape_string($conn, $_POST['client_name']);
    $state             = mysqli_real_escape_string($conn, $_POST['state']);
    $district          = mysqli_real_escape_string($conn, $_POST['district']);
    $city              = mysqli_real_escape_string($conn, $_POST['city']);
    $address           = mysqli_real_escape_string($conn, $_POST['address']);
    $uses_amt          = mysqli_real_escape_string($conn, $_POST['uses_amt']);
    $status            = mysqli_real_escape_string($conn, $_POST['status']);
    $project_name      = mysqli_real_escape_string($conn, $_POST['project_name']);
    $po_date           = mysqli_real_escape_string($conn, $_POST['po_date']);
    $installation_date = mysqli_real_escape_string($conn, $_POST['installation_date']);

    $wall_clean        = mysqli_real_escape_string($conn, $_POST['wall_clean']);
    $seats             = mysqli_real_escape_string($conn, $_POST['seats']);
    $flush_time        = mysqli_real_escape_string($conn, $_POST['flush_time']);
    $floor_time        = mysqli_real_escape_string($conn, $_POST['floor_time']);
    $wall_time         = mysqli_real_escape_string($conn, $_POST['wall_time']);

    // Checkbox values
    $free          = isset($_POST['free']) ? 'Yes' : 'No';
    $coin          = isset($_POST['coin']) ? 'Yes' : 'No';
    $upi           = isset($_POST['upi']) ? 'Yes' : 'No';
    $smart_card    = isset($_POST['smart_card']) ? 'Yes' : 'No';
    $digital_token = isset($_POST['digital_token']) ? 'Yes' : 'No';

    $insertQuery = "INSERT INTO machines 
    (
        machine_id, client_name, state, district, city, address,
        uses_amt, status, project_name, po_date, installation_date,
        wall_clean, seats, flush_time, floor_time, wall_time,
        free, coin, upi, smart_card, digital_token
    )
    VALUES
    (
        '$machine_id', '$client_name', '$state', '$district', '$city', '$address',
        '$uses_amt', '$status', '$project_name', '$po_date', '$installation_date',
        '$wall_clean', '$seats', '$flush_time', '$floor_time', '$wall_time',
        '$free', '$coin', '$upi', '$smart_card', '$digital_token'
    )";

    if (mysqli_query($conn, $insertQuery)) {
        echo "<script>alert('Machine added successfully'); window.location='list_machinedetails.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error { color: red; font-size: 0.9rem; }
    input[readonly], textarea[readonly] {
     background-color: #f1f1f1 !important;  /* Light Gray */
    color: #000 !important;              /* Normal dark text */
    opacity: 1 !important;               /* Remove faded effect */
    cursor: default;
}
  </style>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
<?php include 'include/sidebar.php'; ?>
<?php include 'include/header.php'; ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Add Machine Details</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    
      <div class="row">
          <div class="card">
            <div class="card-body">

         
        <form id="vendorForm"  method="POST" enctype="multipart/form-data">
          <div class="row">

            <div class="col-md-4 mb-3">
              <label>Machine Id</label>
              <input type="text" name="machine_id" id="machine_id" placeholder="Enter Machine name" maxlength="20" class="form-control" required>
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
  <textarea name="address" id="address" placeholder="Enter Address" maxlength="150" readonly class="form-control" rows="1" required></textarea>
</div>


<!-- New Project Name Field -->
    <div class="col-md-4 mb-3">
      <label>Project Name</label>
     
       <select class="form-control" name="project_name" id="project_name" required>
    <option value="">-- Select Project Name --</option>
    <?php
    $stateQuery = "SELECT DISTINCT project_name, project_starts FROM projects";
    $stateResult = mysqli_query($conn, $stateQuery);

    while ($row = mysqli_fetch_assoc($stateResult)) {
        echo '<option 
                value="' . htmlspecialchars($row['project_name']) . '"
                data-projectdate="' . htmlspecialchars($row['project_starts']) . '"
             >' 
             . htmlspecialchars($row['project_name']) . 
             '</option>';
    }
    ?>
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
      <input type="date" name="installation_date" id="installation_date" class="form-control" required>
    </div>
    <div class="col-md-4 mb-3">
  <label>Amount</label>
  <input type="text" 
         name="uses_amt" 
         id="uses_amt" 
         placeholder="Enter Amount" 
         maxlength="3" 
         class="form-control" 
         required
         oninput="this.value = this.value.replace(/\D/g, '').slice(0,3);">
</div>

   



          

            <!-- New Status Field -->
            <div class="col-md-4 mb-3">
              <label>Status</label>
              <select name="status" class="form-control" required>
                <option value="">-- Select Status --</option>
                <option value="Ready">Ready</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Busy">Busy</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
  <label>Wall Clean</label>
  <input type="text" 
         name="wall_clean" 
         id="wall_clean"
         maxlength="10" 
         placeholder="Enter Wall Clean" 
         class="form-control" 
         required>
</div>

<div class="col-md-4 mb-3">
  <label>No. Of Seats</label>
 <input type="text" 
       name="seats" 
       id="seats" 
       maxlength="2" 
       placeholder="Enter Seats" 
       class="form-control" 
       required
       oninput="this.value = this.value.replace(/[^0-9]/g, '');">
</div>

<div class="col-md-4 mb-3">
  <label>Flush Time</label>
  <input type="text" 
         name="flush_time" 
         id="flush_time" 
         class="form-control" 
         maxlength="3"
         required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
</div>

<div class="col-md-4 mb-3">
  <label>Floor Time</label>
  <input type="text" 
         name="floor_time" 
         id="floor_time" 
         class="form-control"
         maxlength="3" 
         required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
</div>

<div class="col-md-4 mb-3">
  <label>Wall Time</label>
  <input type="text" 
         name="wall_time" 
         id="wall_time" 
         class="form-control" 
         maxlength="3" 
         required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
</div>
 <div class="col-md-8 mb-3">
  <label><strong>Payment Modes</strong></label><br>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="free" id="free" value="Yes">
    <label class="form-check-label" for="free">Free</label>
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
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="list_machinedetails.php" class="btn btn-secondary ms-2">Back</a>
          </div>
        </form>
      

              

        
        
        
        
        
        
        </div>
          </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->


<?php include 'include/footer.php'; ?>
<?php include 'include/jsc.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  document.getElementById('client_name').addEventListener('change', function () {

    var selected = this.options[this.selectedIndex];

    document.getElementById('state').value    = selected.getAttribute('data-state') || '';
    document.getElementById('district').value = selected.getAttribute('data-district') || '';
    document.getElementById('city').value     = selected.getAttribute('data-city') || '';
    document.getElementById('address').value  = selected.getAttribute('data-address') || '';
});

  document.getElementById('project_name').addEventListener('change', function () {
    let selected = this.options[this.selectedIndex];
    let date = selected.getAttribute('data-projectdate');

    document.getElementById('po_date').value = date ? date : '';
});


  document.getElementById('free').addEventListener('change', function () {
    const isChecked = this.checked;

    // Disable other checkboxes when FREE is checked
    document.getElementById('coin').disabled = isChecked;
    document.getElementById('upi').disabled = isChecked;
    document.getElementById('smart_card').disabled = isChecked;
    document.getElementById('digital_token').disabled = isChecked;

    // Also uncheck them when disabled
    if (isChecked) {
        document.getElementById('coin').checked = false;
        document.getElementById('upi').checked = false;
        document.getElementById('smart_card').checked = false;
        document.getElementById('digital_token').checked = false;
    }
});
 </script>
    
</body>
</html>
