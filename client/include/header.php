<?php
ob_start();
//session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Smart Toilet </title>

    <!-- Prevent FOUC by setting theme immediately -->
    <script>
        (function() {
            var theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Cambria&display=swap" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="js/jquery.dataTables.min.js"></script>
  <link href="css/custom.css?v=<?= time() ?>" rel="stylesheet">
<!-- Column Chart  -->          
  <script src="js/column_chart_js/jquery-2.1.4.js"></script>
  <script src="js/column_chart_js/fusioncharts.js"></script>
  <script src="js/column_chart_js/fusioncharts.charts.js"></script>
  <script src="js/column_chart_js/themes/fusioncharts.theme.zune.js"></script>  
  <script src="js/column_chart_js/app.js"></script>
  <!-- Export PDF  picker  -->
    <link href="css/theme.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">