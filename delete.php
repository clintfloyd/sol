<?php
session_start();
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
require('mysql/MysqliDb.php');
require('mysql_connection.php');
$db->autoReconnect = true;

$db->where('transfer_id', $_GET['id']);
$db->delete('transfer_request_items');


$db->where('request_id', $_GET['id']);
$db->delete('transfer_request');


header("Location: transfers.php");
