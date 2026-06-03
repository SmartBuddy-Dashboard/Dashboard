<?php
include('include/config.php');

/* ==============================
   FETCH USER DATA
============================== */
if (!isset($_GET['updateid'])) {
    die('Invalid Request');
}

$id = mysqli_real_escape_string($conn, $_GET['updateid']);

$query  = "SELECT * FROM tblusers WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (!$row = mysqli_fetch_assoc($result)) {
    die('User not found');
}

/* Assign variables */
$name     = $row['name'];
$email    = $row['email'];
$mobile   = $row['mobile'];
$password = $row['password'];
$role     = $row['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<div class="container mt-5">

<form method="POST" action="update_user.php">
<input type="hidden" name="id" value="<?php echo $id; ?>">

    <!-- NAME -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Full Name</label>
        <div class="col-md-9">
            <input type="text" name="name" maxlength="50"
                   class="form-control"
                   value="<?= htmlspecialchars($name); ?>" required>
        </div>
    </div>

    <!-- EMAIL -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Email</label>
        <div class="col-md-9">
            <input type="email" name="email" maxlength="50"
                   class="form-control"
                   value="<?= htmlspecialchars($email); ?>" required>
        </div>
    </div>

    <!-- MOBILE -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Mobile</label>
        <div class="col-md-9">
            <input type="text" name="mobile" maxlength="10"
                   class="form-control"
                   value="<?= htmlspecialchars($mobile); ?>"
                   oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                   required>
        </div>
    </div>

    <!-- PASSWORD -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Password</label>
        <div class="col-md-9">
            <input type="text" name="password" maxlength="20"
                   class="form-control"
                   value="<?= htmlspecialchars($password); ?>" required>
        </div>
    </div>

    <!-- ROLE -->
    <div class="row mb-3 align-items-center">
        <label class="col-md-3 fw-bold">Role</label>
        <div class="col-md-9">
             <input type="text" name="role" maxlength="20"
                   class="form-control"
                   value="<?= htmlspecialchars($role); ?>" readonly>

        </div>
    </div>

    <!-- BUTTONS -->
    <div class="text-center mt-4">
        <button type="submit" name="update" class="btn btn-primary px-4">Update</button>
        
    </div>

</form>

</div>

</body>
</html>