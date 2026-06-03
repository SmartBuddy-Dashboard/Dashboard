<?php
include('include/config.php');

if (isset($_POST['update'])) {

    // Collect and sanitize input
    $id             = mysqli_real_escape_string($conn, $_POST['id']);
    $client_name    = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_phone   = mysqli_real_escape_string($conn, $_POST['client_phone']);
    $client_address = mysqli_real_escape_string($conn, $_POST['client_address']);
    $client_website = mysqli_real_escape_string($conn, $_POST['client_website']);
    $state          = mysqli_real_escape_string($conn, $_POST['state']);
    $district       = mysqli_real_escape_string($conn, $_POST['district']);
    $city           = mysqli_real_escape_string($conn, $_POST['city']);
    $client_type    = mysqli_real_escape_string($conn, $_POST['client_type']);
    $contact_name   = mysqli_real_escape_string($conn, $_POST['contact_name']);
    $contact_mobile = mysqli_real_escape_string($conn, $_POST['contact_mobile']);
    $contact_email  = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $password       = mysqli_real_escape_string($conn, $_POST['password']);

/* --------------------------------
   CLIENT LOGO UPLOAD
----------------------------------*/
$client_logo = $_POST['old_logo'] ?? '';

if (!empty($_FILES['client_logo']['name'])) {

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower(pathinfo($_FILES['client_logo']['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        echo "<script>alert('Only JPG, JPEG, PNG files allowed'); history.back();</script>";
        exit;
    }

    /* ===== CLEAN CLIENT NAME ===== */
    $safeClientName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($client_name));

    /* ===== FINAL FILE NAME (id_clientname.ext) ===== */
    $fileName = $id . '_' . $safeClientName . '.' . $fileExt;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['client_logo']['tmp_name'], $targetPath)) {

        // delete old logo if exists and different
        if (!empty($_POST['old_logo']) && 
            $_POST['old_logo'] !== $fileName && 
            file_exists($uploadDir . $_POST['old_logo'])) {

            unlink($uploadDir . $_POST['old_logo']);
        }

        $client_logo = $fileName;
    } else {
        echo "<script>alert('Logo upload failed'); history.back();</script>";
        exit;
    }
}


    /* --------------------------------
       UPDATE QUERY
    ----------------------------------*/
    $sql = "UPDATE clients SET 
                client_name     = '$client_name',
                client_phone    = '$client_phone',
                client_address  = '$client_address',
                client_website  = '$client_website',
                contact_email   = '$contact_email',
                client_state    = '$state',
                clinet_district = '$district',
                client_city     = '$city',
                client_type     = '$client_type',
                contact_person  = '$contact_name',
                password        = '$password',
                contact_mobile  = '$contact_mobile',
                client_logo     = '$client_logo'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Client updated successfully'); window.location='list_client.php';</script>";
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

} else {
    echo "Invalid Request!";
}
?>
