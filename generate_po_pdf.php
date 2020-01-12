<?php
session_start();
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
//setlocale(LC_MONETARY, 'ar-AE');

require ("../vendor/autoload.php");
require('mysql_connection.php');
$db->autoReconnect = true;
$db->join("products prod", "prod.sku=item.sku", "LEFT");
$db->where("transfer_id",$_GET['id']);
$results = $db->get('po_request_items item', null, "item.id, prod.sku, prod.parent_name, prod.supplier_price_php, prod.product_name, item.qty, item.transfer_id, item.received, item.remaining, item.date_added");

$tableBody = "";
$qtyTotal = 0;
$computedTotal = 0;
foreach($results as $result){

  $subTotal = $result['qty']*$result['supplier_price_php'];
  $qtyTotal += $result['qty'];
  $computedTotal += $subTotal;

  $tableBody .= "<tr>";
  $tableBody .= "<td>".$result['parent_name']." ".$result['product_name']."</td>";
  $tableBody .= "<td style='text-align:right;'>".number_format($result['supplier_price_php'], 2)."</td>";
  $tableBody .= "<td style='text-align:center;'>".$result['qty']."</td>";
  $tableBody .= "<td style='text-align:right;'>".number_format( $subTotal, 2 )."</td>";
  $tableBody .= "</tr>";
}
//footer
$tableBody .= "<tfoot><tr>";
$tableBody .= "<td colspan='2' style='background: #343a40; font-weight: bold; color: #fff;text-align:right;'>TOTAL:</td>";
$tableBody .= "<td style='background: #343a40; font-weight: bold; color: #fff;text-align:center;'>".$qtyTotal."</td>";
$tableBody .= "<td style='background: #343a40; font-weight: bold; color: #fff;text-align:right;'>PhP ".number_format($computedTotal, 2 )."</td>";
$tableBody .= "</tr></tfoot>";


//get Supplier
$db->where("request_id",$_GET['id']);
$poDetails = $db->get('po_request');
//get PO Number
$db->where("id",$poDetails[0]['vendor_id']);
$vendors = $db->get('vendors');

$vendorDetails = $vendors[0]['company_name']."<br/>".$vendors[0]['name']."<br/>".$vendors[0]['address']."<br/>".$vendors[0]['email'];

$poNumber = date("Ymd", strtotime($poDetails[0]['date'])) . "-00" . $poDetails[0]['id'];
$poDate = date("j F Y", strtotime($poDetails[0]['date']));

use Dompdf\Dompdf;



// instantiate and use the dompdf class
$dompdf = new Dompdf();

$html = file_get_contents("pdf/pdf-po.php");

$html = str_replace("##BODY##",$tableBody, $html);
$html = str_replace("##PONUMBER##",$poNumber, $html);
$html = str_replace("##DATE##",$poDate, $html);
$html = str_replace("##SUPPLIER##",$vendorDetails, $html);

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
