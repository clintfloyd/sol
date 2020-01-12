<?php
session_start();
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
require('mysql/MysqliDb.php');
require('mysql_connection.php');
$db->autoReconnect = true;


$data = array("is_readonly"=>"true");
$db->where ('request_id', $_GET['id']);


$db->update ('po_request', $data);

header("Location: po.php");
die();
