<?php
require('mysql/MysqliDb.php');
$db = new MysqliDb ('localhost', 'root', 'root', 'bd_inventory');
$db->autoReconnect = true;
$db->where ('parent_name', "%" . $_POST['q'] . "%" , "like");
$db->orWhere ('product_name', "%" . $_POST['q'] . "%" , "like");
$results = $db->get('products');
//$results = array("results" => $results);
echo json_encode($results);
?>
