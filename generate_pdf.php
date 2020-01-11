<?php
require ("../vendor/autoload.php");


$db = new MysqliDb ('localhost', 'root', 'root', 'bd_inventory');
$db->autoReconnect = true;
$db->join("products prod", "prod.sku=item.sku", "LEFT");
$db->where("transfer_id",$_GET['id']);
$results = $db->get('transfer_request_items item', null, "item.id, prod.sku, prod.parent_name, prod.supplier_price_php, prod.product_name, item.qty, item.transfer_id, item.received, item.remaining, item.date_added");

$tableBody = "";
foreach($results as $result){
  $tableBody .= "<tr>";
  $tableBody .= "<td>".$result['parent_name']." ".$result['product_name']."</td>";
  $tableBody .= "<td>".$result['parent_name']." ".$result['product_name']."</td>";
  $tableBody .= "<td>".$result['supplier_price_php']."</td>";
  $tableBody .= "<td>".$result['qty']."</td>";
  $tableBody .= "<td>".($result['qty']*$result['supplier_price_php'])."</td>";
  $tableBody .= "</tr>";
}

use Dompdf\Dompdf;



// instantiate and use the dompdf class
$dompdf = new Dompdf();

$html = file_get_contents("pdf/pdf-transfer.php");

$html = str_replace("##BODY##",$tableBody, $html);

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
