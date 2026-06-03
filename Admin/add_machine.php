<?php
include('include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
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

    // Insert into database
    $insertQuery = "INSERT INTO machines 
        (machine_id, client_name, state, district, city, address, uses_amt, status, project_name, po_date, installation_date) 
        VALUES 
        ('$machine_id', '$client_name', '$state', '$district', '$city', '$address', '$uses_amt', '$status', '$project_name', '$po_date', '$installation_date')";

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

            <div class="col-md-6 mb-3">
              <label>Machine Id</label>
              <input type="text" name="machine_id" id="machine_id" placeholder="Enter Machine name" maxlength="20" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
              <label>Client Name</label>
              
              <select class="form-control" name="client_name" id="client_name" required>
                <option value="">-- Select Client Name --</option>
                <?php
                $stateQuery = "SELECT DISTINCT client_name FROM clients ";
                $stateResult = mysqli_query($conn, $stateQuery);
                while ($row = mysqli_fetch_assoc($stateResult)) {
                    echo '<option value="' . htmlspecialchars($row['client_name']) . '">' . htmlspecialchars($row['client_name']) . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label>State</label>
              <select class="form-control" name="state" id="state" required onchange="fetchDistricts(this.value)">
                <option value="">-- Select State --</option>
                <?php
                $stateQuery = "SELECT DISTINCT state FROM cities ORDER BY state ASC";
                $stateResult = mysqli_query($conn, $stateQuery);
                while ($row = mysqli_fetch_assoc($stateResult)) {
                    echo '<option value="' . htmlspecialchars($row['state']) . '">' . htmlspecialchars($row['state']) . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label>District</label>
              <select class="form-control" name="district" id="district" required onchange="fetchCities(this.value)">
                <option value="">-- Select District --</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label>City</label>
              <select class="form-control" name="city" id="city" required>
                <option value="">-- Select City --</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
  <label>Address</label> 
  <textarea name="address" id="address" placeholder="Enter Address" maxlength="150" class="form-control" rows="1" required></textarea>
</div>
 

<!-- New Project Name Field -->
    <div class="col-md-6 mb-3">
      <label>Project Name</label>
     
       <select class="form-control" name="project_name" id="project_name" required>
                <option value="">-- Select Project Name --</option>
                <?php
                $stateQuery = "SELECT DISTINCT project_name FROM projects ";
                $stateResult = mysqli_query($conn, $stateQuery);
                while ($row = mysqli_fetch_assoc($stateResult)) {
                    echo '<option value="' . htmlspecialchars($row['project_name']) . '">' . htmlspecialchars($row['project_name']) . '</option>';
                }
                ?>
              </select>
    </div>

    <!-- New PO Date Field -->
    <div class="col-md-6 mb-3">
      <label>Project Date</label>
      <input type="date" name="po_date" id="po_date" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
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

    <!-- New Installation Date Field -->
    <div class="col-md-6 mb-3">
      <label>Installation Date</label>
      <input type="date" name="installation_date" id="installation_date" class="form-control" required>
    </div>



          

            <!-- New Status Field -->
            <div class="col-md-6 mb-3">
              <label>Status</label>
              <select name="status" class="form-control" required>
                <option value="">-- Select Status --</option>
                <option value="Ready">Ready</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Busy">Busy</option>
              </select>
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
   function fetchDistricts(state) {
      if (state) {
        $.ajax({
          type: 'POST',
          url: 'fetch_districts.php',
          data: { state: state },
          success: function(response) {
            $('#district').html(response);
            $('#city').html('<option value="">-- Select City --</option>');
          }
        });
      } else {
        $('#district').html('<option value="">-- Select District --</option>');
        $('#city').html('<option value="">-- Select City --</option>');
      }
   }

   function fetchCities(district) {
      if (district) {
        $.ajax({
          type: 'POST',
          url: 'fetch_cities.php',
          data: { district: district },
          success: function(response) {
            $('#city').html(response);
          }
        });
      } else {
        $('#city').html('<option value="">-- Select City --</option>');
      }
   }

 </script>
    
</body>
</html>
