<?php
include('include/config.php');

$id = $_GET['updateid'];
$query = "SELECT * FROM machines WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $old_machine_id   = $row["machine_id"];
   
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <title>Update Machine Details</title>
</head>
<body>

<div class="container mt-5">
<form action="verify_machine.php" method="post" enctype="multipart/form-data" >

<input type="hidden" name="id" value="<?php echo $id; ?>">

<div class="row">

<!---------------- Machine ID ------------------>
<div class="col-md-6 mb-3">
    <label>Current Machine ID</label>
    <input type="text" name="old_machine_id" class="form-control" maxlength="20"
           value="<?php echo htmlspecialchars($old_machine_id); ?>" readonly required>
</div>

<div class="col-md-6 mb-3">
              <label>Machine Id</label>
              <input type="text" name="new_machine_id" id="new_machine_id" placeholder="Enter Machine name" maxlength="20" class="form-control" required>
               
            </div>



</div>
<br>

<div class="text-center">
    <button type="submit" name="update" class="btn btn-primary">Verify</button>
</div>

</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</body>
</html>
