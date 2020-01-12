<?php
session_start();
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
require('mysql/MysqliDb.php');
require('mysql_connection.php');
$db->autoReconnect = true;
$db->where ('parent_name', "%" . $_POST['q'] . "%" , "like");
$db->orWhere ('product_name', "%" . $_POST['q'] . "%" , "like");
$results = $db->get('products');
//$results = array("results" => $results);
echo json_encode($results);
?>
