<?php
include('include/config.php');

/* =========================
   UPDATE USER
========================= */

if (isset($_POST['update'])) {

    $id       = mysqli_real_escape_string($conn, $_POST['id']);
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // ✅ Update Query
    $query = "UPDATE tblusers SET 
                name='$name',
                email='$email',
                mobile='$mobile',
                password='$password',
                role='$role'
              WHERE id='$id'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
                alert('User Updated Successfully');
                window.location.href='list_user.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: ".mysqli_error($conn)."');
                window.history.back();
              </script>";
    }

} else {
    echo "Invalid Request";
}
?>