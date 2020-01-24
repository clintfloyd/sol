<?php
session_start();
global $db;
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
require('mysql/MysqliDb.php');
require('mysql_connection.php');

$location_id = $_SESSION['location'];

$db->autoReconnect = true;
$db->join("products prod", "prod.sku=item.sku", "LEFT");
$db->where("transfer_id",$_GET['id']);
$results = $db->get('transfer_request_items item', null, "item.id, prod.sku, prod.parent_name, prod.supplier_price_php, prod.product_name, item.qty, item.transfer_id, item.received, item.remaining, item.date_added");


$date = date("r");


$db->where("request_id",$_GET['id']);
$order_details = $db->get('transfer_request');


$transferStatus = $order_details[0]['status'];
if($transferStatus==='completed'){
  $db->where("transfer_id",$_GET['id']);
  $db->orderBy("id", "desc");
  $transDataComplete = $db->get('transfer_confirmation');
}

function getLocationName($id){
  global $db;
  $db->where("id",$id);
  $results = $db->get('locations');
  return $results[0]['location_name'];
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
  <div class="container white">
    <div style="margin-bottom: 50px;">
      <h4>Received Stocks From:</h4>
      <h1><?php echo getLocationName($order_details[0]['location_id']); ?></h1>
    </div>
    <form method="post" action="receive_stocks_signature.php">

      <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>SKU</th>
            <th>Item Name</th>
            <th class="text-center">Requested</th>
            <th class="text-center">Received</th>
            <th class="text-center">Total Received</th>
            <th class="text-center">Remaining</th>
            <th class="text-center">Date</th>
          </tr>
        </thead>
        <tbody class="">
          <?php
          $totalQTY = 0;
          foreach($results as $result){
            $totalReceived = 0;
            //query
            $db->where("sku",$result['sku']);
            $db->Where("transfer_id",$result['transfer_id']);
            $itemMovement = $db->get('transfer_movement_per_item');
            $itemMovementCount = $db->count;
            if($itemMovementCount >= 1){
              $currentReq = ($result['qty']);
              $tmpQTY = $currentReq-0;



              foreach($itemMovement as $im){
                ?>
                <tr>
                  <td><?php echo $im['id']; ?></td>
                  <td><?php echo $result['sku']; ?></td>
                  <td><?php echo $result['parent_name']; ?> - <?php echo $result['product_name']; ?></td>
                  <td class="text-center qtyContainer"></td>
                  <td class="text-center qtyContainer"></td>
                  <td class="text-center">
                    <?php echo $im['received']; ?>
                  </td>
                  <td>&nbsp;</td>
                  <td colspan="" class="text-center"><?php echo date("j F Y - g:i A", strtotime($im['date']) ); ?></td>
                <?php

                  // $rem = ($im['remaining']);
                  // $tmpQTY = $currentReq-$rem;

                  $totalReceived += $im['received'];
              }


            }
            $totalQTY += $result['qty'];

            if( $totalReceived >= $result['qty']){

              ?>

              <tr class="table-success">
                <td><?php echo $result['id']; ?></td>
                <td><?php echo $result['sku']; ?></td>
                <td><?php echo $result['parent_name']; ?> - <?php echo $result['product_name']; ?></td>
                <td class="text-center origQTY"> <?php
                  $computation =  $result['qty']-$totalReceived;
                  if($computation <= 0){
                    $computation = 0;
                  }
                  echo $result['qty'];
                ?></td>
                <td class="text-center receivedSoFar">
                  <?php
                  echo $totalReceived;
                  ?>
                </td>
                <td class="text-center">
                  <?php
                  echo $totalReceived;
                  ?>
                </td>
                <td class="text-center">
                  <?php
                  $tmpTotalReceivedComputed = $result['qty']-$totalReceived;

                  if($tmpTotalReceivedComputed<0){
                    ?>
                    <span class="badge badge-info">Excess: </span>
                    <span><?php echo abs($tmpTotalReceivedComputed); ?></span>
                    <?php
                  }else{
                    echo "0";
                  }
                  ?>
                </td>
                <td class="text-center"><?php echo date("j F Y - g:i A", strtotime($result['date_added']) ); ?></td>
              </tr>

              <?php


            }else{
          ?>
          <tr>
            <td><?php echo $result['id']; ?></td>
            <td><?php echo $result['sku']; ?></td>
            <td><?php echo $result['parent_name']; ?> - <?php echo $result['product_name']; ?></td>
            <td class="text-center origQTY"> <?php
              $computation =  $result['qty']-$totalReceived;
              if($computation <= 0){
                $computation = 0;
              }
              echo $result['qty'];
            ?></td>
            <td class="text-center ">
              <?php
              echo $totalReceived;
              ?>
            </td>
            <td class="text-center">
              &nbsp;
            </td>
            <td class="text-center">
              &nbsp;
            </td>
            <td><?php echo date("j F Y - g:i A", strtotime($result['date_added']) ); ?></td>
          </tr>
        <?php }
          } ?>
        </tbody>
      </table>
      <div class="text-right">
        <?php if($transferStatus == "completed"){ ?>
          <div class="text-left">
            <small>Received by: <?php echo $transDataComplete[0]['signed_by']; ?> (<?php echo date("j F Y - g:i A", strtotime($transDataComplete[0]['date'])); ?>)</small>
            <img class="sigImage" src="<?php echo $transDataComplete[0]['signature']; ?>" />
          </div>
        <?php } else{ ?>

        <?php } ?>
      </div>
    </form>
  </div>
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

  <script>
      $(function(){

        var timeout = 0;


        computeReceived = function(){
          var total = 0;
          $(".received").each(function(){
            var tmpVal = parseInt($(this).val());
            if(!isNaN(tmpVal)){
              total += parseInt(tmpVal);
            }
          });
          $(".totalReceived").html(total);
        }


        $(".received").keyup(function(){
            var obj = $(this);
            var reqObj = parseInt(obj.parent().prev().prev().html());
            var receivedObj = parseInt(obj.parent().prev().html());

            var receivedItems = parseInt(reqObj)-parseInt(receivedObj);

            var remObj = obj.parent().next("td").children(".remaining");
            var objValue = parseInt(obj.val());
            //compute remaining
            if(!isNaN(objValue)){
              var rem = receivedItems - objValue;
              rem = rem < 0 ? 0 : rem;
              remObj.val(rem);
            }

            computeReceived();
      	});

      });
  </script>
</body>
</html>
