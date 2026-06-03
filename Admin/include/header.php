<?php
ob_start();
session_start();

// ----------------------------
// Check if user is logged in
// ----------------------------
if (!isset($_SESSION['mobile'])) {
    echo "<script>alert('User not logged in!'); window.location='index.php';</script>";
    exit();
}

$mobile = $_SESSION['mobile'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Smart Toilet</title>

    <!-- Prevent FOUC by setting theme immediately -->
    <script>
        (function() {
            var theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/theme.css?v=<?= time() ?>" rel="stylesheet">
    <!-- Premium Industrial UI -->
    <link href="../assets/css/premium-industrial.css?v=<?= time() ?>" rel="stylesheet">

    <!-- Column Chart  -->
    <script src="js/column_chart_js/jquery-2.1.4.js"></script>
    <script src="js/column_chart_js/fusioncharts.js"></script>
    <script src="js/column_chart_js/fusioncharts.charts.js"></script>
    <script src="js/column_chart_js/themes/fusioncharts.theme.zune.js"></script>  
    <script src="js/column_chart_js/app.js"></script>

    <!-- -----------------------------
         JavaScript Session Timeout + AJAX Logout
    ------------------------------- -->
<script>
const INACTIVITY_LIMIT = 30 * 60 * 1000; // 30 minutes ✅
let inactivityTimer;

function resetTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(logoutUser, INACTIVITY_LIMIT);
}

function logoutUser() {
    // redirect to logout
    window.location.href = "logout.php";
}

// Better event listeners (recommended)
window.addEventListener('load', resetTimer);
document.addEventListener('mousemove', resetTimer);
document.addEventListener('keypress', resetTimer);
document.addEventListener('scroll', resetTimer);
document.addEventListener('click', resetTimer);
</script>

</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
