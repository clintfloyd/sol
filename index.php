<?php
require('mysql/MysqliDb.php');
$db = new MysqliDb ('localhost', 'root', '', 'bd_inventory');
$db->autoReconnect = true;
$results = $db->get('products');
print_r($results);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Stocks</title>
  <link rel="stylesheet" href="css/bootstrap-reboot.min.css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrap-grid.min.css" />
</head>
<body>
  <div class="container">
    hello world
  </div>
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
