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
$db->where("location_id",$location_id);
$db->orderBy("id", "asc");
$results = $db->get('transfer_request');


$db->where("vendor_id",$location_id);
$db->where("status !='completed'");
$db->where("type ='transfer'");
$db->orderBy("id", "asc");
$reqs = $db->get('transfer_request');


$db->where("vendor_id",$location_id);
$db->where("type","requesting");
$db->where("status !='completed'");
$db->orderBy("id", "asc");
$stockRequest = $db->get('transfer_request');


$db->where("vendor_id",$location_id);
$db->where("type","transfer");
$db->where("status ='completed'");
$db->orderBy("id", "asc");
$receivedStocks = $db->get('transfer_request');


$date = date("r");


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
    <ul class="nav justify-content-center mb-3">
      <li class="nav-item">
        <a class="nav-link active" href="#">All  Stock Requests</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="transfer_request.php">Stock Request</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="transfer_stocks.php">Transfer Stocks</a>
      </li>
    </ul>


    <?php if(count($stockRequest) > 0) { ?>

      <div class="receivables">
        <h2>Stock Request</h2>

        <table class="table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>Transfer ID</th>
              <th class="text-center">From</th>
              <th class="text-center">To</th>
              <th class="text-center">Date Added</th>
            </tr>
          </thead>
          <tbody class="">
            <?php foreach($stockRequest as $result){ ?>
            <tr>
              <td><a href="view.php?id=<?php echo $result['request_id']; ?>">#000<?php echo $result['id']; ?></a></td>
              <td class="text-center"><?php echo getLocationName($result['location_id']); ?></td>
              <td class="text-center"><?php echo getLocationName($result['vendor_id']); ?></td>
              <td class="text-center"><?php echo date("j F Y - g:i A", strtotime($result['date_added'])); ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
  <?php } ?>


    <?php if(count($reqs) > 0) { ?>

      <div class="receivables">
        <h2>Stocks Transfer</h2>

        <table class="table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>Transfer ID</th>
              <th class="text-center">From</th>
              <th class="text-center">To</th>
              <th class="text-center">Date Added</th>
              <th class="text-center">Status</th>
              <th class="text-center">&nbsp;</th>
            </tr>
          </thead>
          <tbody class="">
            <?php foreach($reqs as $result){ ?>
            <tr>
              <td><a href="receive_stock_transfer.php?id=<?php echo $result['request_id']; ?>">#000<?php echo $result['id']; ?></a></td>
              <td class="text-center"><?php echo getLocationName($result['location_id']); ?></td>
              <td class="text-center"><?php echo getLocationName($result['vendor_id']); ?></td>
              <td class="text-center"><?php echo date("j F Y - g:i A", strtotime($result['date_added'])); ?></td>
              <td class="text-center"><?php echo $result['status']; ?></td>
              <td>
                <?php if($result['status'] == "completed"){ ?>
                <?php }else if($result['status'] == 'transfer'){ ?>
                  hello
                <?php }else{ ?>
                  <a href="receive_stock_transfer.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-success">Receive</a>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
  <?php } ?>

    <?php if(count($receivedStocks) > 0) { ?>

      <div class="">
        <h2>Received Stocks</h2>

        <table class="table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>Transfer ID</th>
              <th class="text-center">From</th>
              <th class="text-center">To</th>
              <th class="text-center">Date Added</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody class="">
            <?php foreach($receivedStocks as $result){ ?>
            <tr>
              <td><a href="received_stocks_details.php?id=<?php echo $result['request_id']; ?>">#000<?php echo $result['id']; ?></a></td>
              <td class="text-center"><?php echo getLocationName($result['location_id']); ?></td>
              <td class="text-center"><?php echo getLocationName($result['vendor_id']); ?></td>
              <td class="text-center"><?php echo date("j F Y - g:i A", strtotime($result['date_added'])); ?></td>
              <td class="text-center"><?php echo $result['status']; ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
  <?php } ?>

    <h2>Requests</h2>

    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Transfer ID</th>
          <th class="text-center">From</th>
          <th class="text-center">To</th>
          <th class="text-center">Date Added</th>
          <th class="text-center">Status</th>
          <th style="width: 300px;">&nbsp;</th>
        </tr>
      </thead>
      <tbody class="">
        <?php foreach($results as $result){ ?>
        <tr>
          <td><a href="receive_stocks.php?id=<?php echo $result['request_id']; ?>">#000<?php echo $result['id']; ?></a></td>
          <td class="text-center"><?php echo getLocationName($result['location_id']); ?></td>
          <td class="text-center"><?php echo getLocationName($result['vendor_id']); ?></td>
          <td class="text-center"><?php echo date("j F Y - g:i A", strtotime($result['date_added'])); ?></td>
          <td class="text-center"><?php echo $result['status']; ?></td>
          <td class="text-right">
            <?php if($result['status'] == "completed"){ ?>
            <?php }else{
              if( $result['requested_location'] ==  $_SESSION['location']){
                if($result['requested_location'] == "requesting"){
                  ?>
                  <a href="receive_stocks.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-success">Receive</a>
                  <?php
                }
                ?>
                <a href="delete.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-danger">Delete</a>
                <a href="generate_pdf.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-primary">PDF</a>
                <?php

              }else if($result['vendor_id'] != $_SESSION['location']){

                ?>
                <a href="delete.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-danger">Delete</a>
                <a href="generate_pdf.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-primary">PDF</a>
              <?php

              ?>
              <a href="receive_stocks.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-success">Receive</a>
              <a href="generate_pdf.php?id=<?php echo $result['request_id']; ?>" class="btn btn-outline-primary">PDF</a>
            <?php
              }
          } ?>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>


  </div>
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

  <script>
      $(function(){

        var timeout = 0;

        $(".searchProd").keyup(function(){

            clearTimeout(timeout);

            $(".suggestion").html("");

            timeout = setTimeout(function () {
              var val = $(".searchProd").val();
              val = $.trim(val);

              if(val.length > 0){
                $.ajax({
              		type: "POST",
              		url: "data.php",
              		data:'q='+$(".searchProd").val(),
              		beforeSend: function(){
              			$("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
              		},
              		success: function(data){
                    var html = "<ul>";
                    res = $.parseJSON(data);
                    $.each(res, function(index, element){
                      console.log(index, element);
                      html += "<li><a href='javascript:;' class='selectData' "+
                                "data-parent='"+element.parent_name+"' "+
                                "data-name='"+element.product_name+"' "+
                                "data-id='"+element.catalog_id+"' "+
                                "data-sku='"+element.sku+"' "+
                                "data-price='"+element.price+"' "+
                              "'>"+element.parent_name + " " + element.product_name+"</a></li>";
                    });
                    html += "</ul>";


                    $("#suggesstion-box").html(html);


              		}
            		});
              }
            }, 1000);

      	});

        addData = function(cat,name,id,price,sku){
          html = '<tr>';
          html += '<td>'+sku+'</td>';
          html += '<td>'+cat+'</td>';
          html += '<td>'+name+'</td>';
          html += '<td><input type="number" name="qty[]" min="1" value="1" class="qty text-center" />';
          html += '<input type="text" name="location_id[]" value="<?php echo $location_id; ?>" />';
          html += '<input type="text" name="transfer_id[]" value="<?php echo sha1($location_id . $date); ?>" />';
          html += '<input type="text" name="sku[]" value="'+sku+'" />';
          html += '<input type="text" name="product_id[]" value="'+id+'" />';
          html += '</td>';
          html += '<td><a href="javascript:;" class="btn btn-outline btn-danger">Delete</a></td>';
          html += '</tr>';
          $(".tableData").append(html);
          $(".suggestion").html("");
          $(".searchProd").val("").focus();
        }

        $(document).on("click",".selectData", function(){
          cat = $(this).attr("data-parent");
          name = $(this).attr("data-name");
          price = $(this).attr("data-price");
          id = $(this).attr("data-id");
          sku = $(this).attr("data-sku");
          addData(cat,name,id,price,sku);
        });

      });
  </script>
</body>
</html>
