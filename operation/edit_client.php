<?php
include('include/config.php');

$id = $_GET['updateid'];
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
    $password      = $row["password"];
    $contact_email      = $row["contact_email"];
    $client_logo      = $row["client_logo"];
}

// Fetch districts
$district_options = '';
$district_query = "SELECT DISTINCT district FROM cities WHERE state = '$state' ORDER BY district ASC";
$district_result = mysqli_query($conn, $district_query);
while ($district_row = mysqli_fetch_assoc($district_result)) {
    $selected_district = ($district_row['district'] === $district) ? 'selected' : '';
    $district_options .= "<option value='" . htmlspecialchars($district_row['district']) . "' $selected_district>" . htmlspecialchars($district_row['district']) . "</option>";
}

// Fetch cities
$city_options = '';
$city_query = "SELECT DISTINCT city FROM cities WHERE district = '$district' ORDER BY city ASC";
$city_result = mysqli_query($conn, $city_query);
while ($city_row = mysqli_fetch_assoc($city_result)) {
    $selected_city = ($city_row['city'] === $city) ? 'selected' : '';
    $city_options .= "<option value='" . htmlspecialchars($city_row['city']) . "' $selected_city>" . htmlspecialchars($city_row['city']) . "</option>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <title>Update Client</title>
  <style>.section-divider {
    font-size: 22px;
    font-weight: 700;
    margin: 30px 0 20px;
    padding-bottom: 10px;
    position: relative;
    color: #2c3e50;
    text-transform: uppercase;
}

.section-divider i {
    margin-right: 10px;
    color: #16a085;
    font-size: 24px;
}

/* Line under title */
.section-divider:after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #16a085, #1abc9c);
    border-radius: 2px;
}
</style>
</head>
<body>
<div class="container mt-5">

    <form id="clientForm" method="POST" action="update_client.php" enctype="multipart/form-data" onsubmit="return validate();">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

<div class="row">
<div class="col-md-6 mb-3">
  <label>Client Name</label>
  <input type="text" name="client_name" id="client_name" class="form-control" maxlength="150"
         value="<?php echo htmlspecialchars($client_name); ?>" required>
</div>

<div class="col-md-6 mb-3">
  <label>Client Phone</label>
  <input type="text" name="client_phone" id="client_phone" class="form-control" maxlength="10"
         value="<?php echo htmlspecialchars($client_phone); ?>"
         oninput="this.value = this.value.replace(/[^0-9]/g, '')">
</div>


<div class="col-md-12 mb-3">
  <label>Client Address</label>
  <textarea name="client_address" id="client_address" class="form-control" rows="1" maxlength="150" required><?php echo htmlspecialchars($client_address); ?></textarea>
</div>

<div class="col-md-6 mb-3">
  <label>Client Website</label>
  <input type="text" name="client_website" id="client_website" class="form-control" maxlength="150"
         value="<?php echo htmlspecialchars($client_website); ?>" >
</div>

<div class="col-md-6 mb-3">
  <label>Client Type</label>
  <select class="form-control" name="client_type" id="client_type" required>
    <option value="">-- Select Type --</option>

    <option value="Government" <?= ($client_type == "Government") ? "selected" : ""; ?>>
        Government
    </option>

    <option value="Private" <?= ($client_type == "Private") ? "selected" : ""; ?>>
        Private
    </option>

    <option value="Individual" <?= ($client_type == "Individual") ? "selected" : ""; ?>>
        Individual
    </option>

    <option value="NGO" <?= ($client_type == "NGO") ? "selected" : ""; ?>>
        NGO
    </option>
</select>

</div>
<div class="section-divider"><i class="fas fa-map"></i> Location Details</div>
 

<div class="row">

<div class="col-md-6 mb-3">
  <label>State</label>
  <select class="form-control" name="state" id="state" required onchange="fetchDistricts(this.value)">
    <option value="">-- Select State --</option>
    <?php
    $stateQuery = "SELECT DISTINCT state FROM cities ORDER BY state ASC";
    $stateResult = mysqli_query($conn, $stateQuery);
    while ($row = mysqli_fetch_assoc($stateResult)) {
        $selected_state = ($row['state'] === $state) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($row['state']) . '" ' . $selected_state . '>' . htmlspecialchars($row['state']) . '</option>';
    }
    ?>
  </select>
</div>

<div class="col-md-6 mb-3">
  <label>District</label>
  <select class="form-control" name="district" id="district" required onchange="fetchCities(this.value)">
    <option value="">-- Select District --</option>
    <?php echo $district_options; ?>
  </select>
</div>

<div class="col-md-6 mb-3">
  <label>City</label>
  <select class="form-control" name="city" id="city" required>
    <option value="">-- Select City --</option>
    <?php echo $city_options; ?>
  </select>
</div>
</div>

<div class="section-divider"><i class="fas fa-user-tie"></i> Contact Person Details</div>
<div class="row">
<div class="col-md-6 mb-3">
  <label>Contact Name</label>
  <input type="text" name="contact_name" id="contact_name" class="form-control" maxlength="50"
         value="<?php echo htmlspecialchars($contact_person); ?>" required>
</div>

<div class="col-md-6 mb-3">
  <label>Contact Mobile</label>
  <input type="text" name="contact_mobile" id="contact_mobile" class="form-control" maxlength="10"
         value="<?php echo htmlspecialchars($contact_mobile); ?>"
         oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
</div>
<div class="col-md-6 mb-3">
    <label>Contact Email</label>
    <input type="email" name="contact_email" id="contact_email" placeholder="Enter Contact Email" maxlength="50" value="<?php echo htmlspecialchars($contact_email); ?>" class="form-control">
   
</div>          
<!-- ✅ New Password Field -->
    <div class="col-md-6 mb-3">
      <label>Password</label>
      <input type="text" name="password" id="password" placeholder="Enter Password" value="<?php echo htmlspecialchars($password); ?>" minlength="8" maxlength="10" class="form-control" required>
      <small class="text-muted">Password must be between 8–10 characters.</small>
    </div>
</div>

<input type="hidden" name="old_logo" value="<?php echo $client_logo; ?>">

 <!-- Upload Field -->
  <div class="col-md-6 mb-3">
    <label>Client Logo</label>
    <input type="file"
           name="client_logo"
           id="client_logo"
           class="form-control"
           accept="image/png, image/jpeg, image/jpg"
           onchange="previewLogo(this)">
    <small class="text-muted">Allowed: JPG, JPEG, PNG</small>

    <input type="hidden" name="old_logo"
           value="<?php echo htmlspecialchars($client_logo); ?>">
  </div>

  <!-- Preview Column -->
  <div class="col-md-6 mb-3 text-center">
    

    <?php if (!empty($client_logo)) { ?>
      <img id="logoPreview"
           src="uploads/<?php echo htmlspecialchars($client_logo); ?>"
           style="max-height:120px; border:1px solid #ccc; padding:6px;">
    <?php } else { ?>
      <img id="logoPreview"
           style="display:none; max-height:120px; border:1px solid #ccc; padding:6px;">
    <?php } ?>
  </div>


</div>

<div class="text-center">
    <button type="submit" name="update" class="btn btn-primary">Update</button>
</div>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function fetchDistricts(state) {
    if(state){
        $.post('fetch_districts.php', {state: state}, function(response){
            $('#district').html(response);
            $('#city').html('<option value="">-- Select City --</option>');
        });
    } else {
        $('#district').html('<option value="">-- Select District --</option>');
        $('#city').html('<option value="">-- Select City --</option>');
    }
}

function fetchCities(district){
    if(district){
        $.post('fetch_cities.php', {district: district}, function(response){
            $('#city').html(response);
        });
    } else {
        $('#city').html('<option value="">-- Select City --</option>');
    }
}

 





function validate() {
  let clientPhone   = document.getElementById("client_phone").value.trim();
  let contactMobile = document.getElementById("contact_mobile").value.trim();
  let clientWebsite = document.getElementById("client_website").value.trim();
  let contactEmail  = document.getElementById("contact_email").value.trim();

  let phoneRegex = /^[6-9]\d{9}$/;
  let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  let websiteRegex = /^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+$/;

  // Validate only if value given
  if (clientPhone !== "" && !phoneRegex.test(clientPhone)) {
    alert("⚠ Client Phone must be 10 digits and start with 6–9.");
    document.getElementById("client_phone").focus();
    return false;
  }

  if (contactMobile !== "" && !phoneRegex.test(contactMobile)) {
    alert("⚠ Contact Mobile must be 10 digits and start with 6–9.");
    document.getElementById("contact_mobile").focus();
    return false;
  }

  if (contactEmail !== "" && !emailPattern.test(contactEmail)) {
    alert("⚠ Invalid email format!");
    document.getElementById("contact_email").focus();
    return false;
  }

  if (clientWebsite !== "" && !websiteRegex.test(clientWebsite)) {
    alert("⚠ Invalid website.\nExample: example.com | www.test.in | http://site.org");
    document.getElementById("client_website").focus();
    return false;
  }

  return true;
}

</script>
</body>
</html>
