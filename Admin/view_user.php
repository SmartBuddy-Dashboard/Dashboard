<?php
include_once('include/config.php');

$id = $_GET['id']; 

$query = "SELECT * FROM tblusers WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {

    $name     = $row["name"];
    $email    = $row["email"];
    $mobile   = $row["mobile"];
    $password = $row["password"];
    $role     = $row["role"];
?>

<style>
/* Wrapper */
.user-view {
  padding: 10px;
}

/* Desktop */
.user-view table {
  width: 50%;
  margin: auto;
  font-size: 14px;
}

.user-view th[colspan="2"] {
  background: #f5f5f5;
  font-size: 18px;
  text-align: center;
}

/* ================= MOBILE VIEW ================= */
@media (max-width: 768px) {

  .user-view table {
    width: 100% !important;
    font-size: 13.5px;
  }

  .user-view table,
  .user-view tbody,
  .user-view tr,
  .user-view td,
  .user-view th {
    display: block;
    width: 100%;
  }

  .user-view tr {
    margin-bottom: 10px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
  }

  .user-view td {
    padding: 7px 10px;
    line-height: 1.4;
    text-align: left;
  }

  .user-view td:first-child {
    font-weight: 600;
    background: #f8f9fa;
    font-size: 13.5px;
  }

  .user-view th[colspan="2"] {
    font-size: 15px;
    padding: 8px;
  }
}
</style>

<div class="table-responsive user-view">

<table class="table table-bordered" cellpadding="8" cellspacing="0">

   

    <tr>
        <td><strong>Name</strong></td>
        <td><?= htmlspecialchars($name ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Email</strong></td>
        <td><?= htmlspecialchars($email ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Mobile</strong></td>
        <td><?= htmlspecialchars($mobile ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Password</strong></td>
        <td><?= htmlspecialchars($password ?? ''); ?></td>
    </tr>

    <tr>
        <td><strong>Role</strong></td>
        <td><?= htmlspecialchars($role ?? ''); ?></td>
    </tr>

</table>



</div>

<?php
} else {
    echo "<h4 class='text-danger text-center'>Error retrieving user details.</h4>";
}
?>