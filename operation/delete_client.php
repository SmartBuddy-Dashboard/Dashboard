<?php
include_once('include/config.php');

$id = $_GET['deleteid'];

$result = mysqli_query($conn, "DELETE FROM clients WHERE id = '$id'");

if ($result) {
    header('location:list_client.php');
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>