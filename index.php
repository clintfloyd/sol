<?php
session_start();
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Transfer Request</title>
  <link rel="stylesheet" href="css/bootstrap-reboot.min.css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrap-grid.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <?php include("header.php"); ?>
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
