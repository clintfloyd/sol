<?php
session_start();
if(!isset($_SESSION['isLoggedin']) && $_SESSION['isLoggedin'] != "true"){
  header("Location: login.php");
}
require('mysql/MysqliDb.php');
require('mysql_connection.php');
$db->autoReconnect = true;
$results = $db->get('products');

$vendors = $db->get('locations');

$location_id = "1";
$date = date("r");


if($_POST){

  $data = [];

  foreach($_POST['sku'] as $index=>$value){
    $data[] = array( "sku" => $_POST['sku'][$index],
                     "product_id" => $_POST['product_id'][$index],
                     "location_id" => $_POST['location_id'][$index],
                     "transfer_id" => $_POST['transfer_id'][$index],
                     "status" => "pending",
                     "qty" => $_POST['qty'][$index],
                     "remaining" => $_POST['qty'][$index]
                    );
  }

  $ids = $db->insertMulti('transfer_request_items', $data);



  $main_request = array(
                         "requested_location"=>$_SESSION['location'],
                         "vendor_id"=>$_POST['vendor_id'],
                         "due_date"=>$_POST['due_date'],
                         "request_id"=>$_POST['transfer_id'][0],
                         "location_id"=>$_POST['location_id'][0],
                       );

  $ids = $db->insert('transfer_request', $main_request);
  header("Location: transfers.php");
  die();
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

    <h1>Stock Request</h1>
    <hr />

    <form method="post" action="transfer_request.php">

    <div class="row mb-4">
      <div class="col-8">
        <strong>Request Stocks From:</strong><br />
        <select class="vendor form-control" name="vendor_id" required="required">
          <option value="">Select a store</option>
          <?php foreach($vendors as $vendor){
            if($vendor['id'] != $_SESSION['location']){
            ?>
            <option value="<?php echo $vendor['id']; ?>"><?php echo $vendor['location_name']; ?></option>
          <?php }
        }?>
        </select>
      </div>
      <div class="col-4">
        <strong>Due Date</strong><br />
        <input type="date" class="form-control" required="required" name="due_date" />
      </div>
    </div>

    <div class="autoSuggest">
      <input type="text" class="searchProd mb-4" placeholder="Search Item Here..." />
      <div id="suggesstion-box" class="suggestion">
      </div>
    </div>
      <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>SKU</th>
            <th>Category</th>
            <th>Name</th>
            <th class="text-center">Qty</th>
            <th style="width: 50px;" class="text-center">&nbsp;</th>
          </tr>
        </thead>
        <tbody class="tableData">
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2" class="text-right">Total:</td>
            <td colspan="3" class="totalItems">0</td>
          </tr>
        </tfoot>
      </table>
      <div class="text-right">
        <button type="submit" class="btn btn-success">Submit Order</button>
      </div>

      <input type="hidden" name="request_id" value="<?php echo sha1($location_id . $date); ?>" />

    </form>
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
          html += '<td class="text-center"><input type="number" name="qty[]" min="1" value="1" class="qty text-center" />';
          html += '<input type="hidden" name="location_id[]" value="<?php echo $location_id; ?>" />';
          html += '<input type="hidden" name="transfer_id[]" value="<?php echo sha1($location_id . $date); ?>" />';
          html += '<input type="hidden" name="sku[]" value="'+sku+'" />';
          html += '<input type="hidden" name="product_id[]" value="'+id+'" />';
          html += '</td>';
          html += '<td><a href="javascript:;" tabindex="-1" class="btn btn-outline btn-danger">Delete</a></td>';
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
