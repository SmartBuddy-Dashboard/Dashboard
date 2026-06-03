<?php
include_once('include/config.php');

if(isset($_POST['client_name']) && isset($_POST['project_name'])){

    $client_name  = $_POST['client_name'];
    $project_name = $_POST['project_name'];

    $query = mysqli_query($conn,
        "SELECT machine_id 
         FROM machines 
         WHERE client_name = '$client_name' 
         AND project_name = '$project_name'"
    );

    echo '<option value="">-- Select Machine --</option>';

    while($row = mysqli_fetch_assoc($query)){
        echo '<option value="'.$row['machine_id'].'">'.$row['machine_id'].'</option>';
    }
}
?>