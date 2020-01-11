<?php
require('mysql/MysqliDb.php');
$db = new MysqliDb ('localhost', 'root', 'root', 'bd_inventory');
$db->autoReconnect = true;

$db->where('transfer_id', $_GET['id']);
$db->delete('transfer_request_items');


$db->where('request_id', $_GET['id']);
$db->delete('transfer_request');


header("Location: transfers.php");
