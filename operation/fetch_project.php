<?php
include_once('include/config.php');

if(isset($_POST['client_name'])){

    $client_name = $_POST['client_name'];

    $query = mysqli_query($conn,
        "SELECT project_name 
         FROM projects 
         WHERE client_name = '$client_name'"
    );

    echo '<option value="">-- Select Project --</option>';

    while($row = mysqli_fetch_assoc($query)){
        echo '<option value="'.$row['project_name'].'">'.$row['project_name'].'</option>';
    }
}
?>