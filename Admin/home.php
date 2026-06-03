<?php 
require_once('include/header.php');

    require_once('include/navbar.php');
    require_once('include/config.php');
    //session_start(); 

// -----------------------------
// DIRECT SESSION CHECK
// -----------------------------
if (!isset($_SESSION['mobile'])) {
    echo "<script>alert('User not logged in!'); window.location='index.php';</script>";
    exit();
}

$mobile = $_SESSION['mobile'];

// Optional: inactivity timeout
$inactive = 900;  // 15 minutes



$_SESSION['timeout'] = time();

 ?>
 <head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 </head>

    <div id="content-wrapper" class="d-flex flex-column">
       <div id="content">

            <div class="container-fluid">
                <div class="card shadow mb-4">
  
                  <div class="row">
                    <img src="img/logo/smart-buddy.jpg" width="100%" alt="Smart Buddy Mascot" > 
                  </div>
  
                </div>
            </div>

        </div>
    </div>
<?php
    include('include/scripts.php');
    include('include/footer.php');
?>
<script>
// Refresh every 1 minute
setTimeout(() => location.reload(), 60000);
</script>



