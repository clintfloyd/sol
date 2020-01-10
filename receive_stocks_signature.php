<?php
require('mysql/MysqliDb.php');
$db = new MysqliDb ('localhost', 'root', '', 'bd_inventory');
$db->autoReconnect = true;

if( isset($_POST['hasSig']) ){
  $mainStatus = "completed";
  foreach($_POST['sku'] as $key=>$value){
    if($_POST['remaining'][$key] <= 0){
      $status = "completed";
    }else{
      $mainStatus = "partial";
      $status = "partial";
    }
    $data = array(
              "remaining" => $_POST['remaining'][$key],
              "received" => $_POST['received'][$key],
              "status" => $status
    );
    $db->where ('id', $_POST['id'][$key]);
    $transItem = $db->update ('transfer_request_items', $data);


    $secondData = array(
        "sku" => $_POST['sku'][$key],
        "transfer_id" => $_POST['transfer_id'][$key],
        "remaining" => $_POST['remaining'][$key],
        "received" => $_POST['received'][$key]
    );
    $confItem = $db->insert ("transfer_movement_per_item", $secondData);




  }

  //update sig
  $sigData = array(
              "signed_by" => "Clint",
              "signature" => $_POST['sig'],
              "transfer_id" => $_POST['transfer_id'][0]
  );
  $confItem = $db->insert ("transfer_confirmation", $sigData);

  //update transfer status
  $transData = array(
              "status" => $mainStatus
  );
  $db->where ('request_id', $_POST['transfer_id'][0]);
  $reqItem = $db->update ('transfer_request', $transData);
  if($confItem && $reqItem){
    header("Location: transfers.php");
    die();
  }

}
echo " .";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Transfer Request</title>
  <link rel="stylesheet" href="css/bootstrap-reboot.min.css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrap-grid.min.css" />
  <link rel="stylesheet" href="css/sig.pad.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="css/style.css" />
</head>
<body style="background: #000;">
  <div class="container cover">
    <form method="post" id="sigForm" action="receive_stocks_signature.php">
      <div id="signature-pad" class="signature-pad">
    <div class="signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="signature-pad--footer">
      <div class="description">Sign above</div>
    </div>
  </div>
  <div class="text-center mt-5">
  <a href="javascript:;" class="btn btn-lg btn-outline-primary clearSig">Clear</a>
  <a href="javascript:;" class="btn btn-lg btn-success saveSig">Save</a>
  <textarea class="sig" name="sig"></textarea>
  <input type="hidden" name="hasSig" value="true" />
  <?php
  foreach($_POST['received'] as $key=> $value){
    ?>
    <input type="hidden" name="received[]" value="<?php echo $value; ?>" />
    <?php
  }
  ?>

  <?php
  foreach($_POST['id'] as $key=> $value){
    ?>
    <input type="hidden" name="id[]" value="<?php echo $value; ?>" />
    <?php
  }
  ?>

  <?php
  foreach($_POST['sku'] as $key=> $value){
    ?>
    <input type="hidden" name="sku[]" value="<?php echo $value; ?>" />
    <?php
  }
  ?>

  <?php
  foreach($_POST['transfer_id'] as $key=> $value){
    ?>
    <input type="hidden" name="transfer_id[]" value="<?php echo $value; ?>" />
    <?php
  }
  ?>

  <?php
  foreach($_POST['remaining'] as $key=> $value){
    ?>
    <input type="hidden" name="remaining[]" value="<?php echo $value; ?>" />
    <?php
  }
  ?>

  </div>
    </form>
  </div>

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

  <script>
  var canvas = document.querySelector("canvas");

  var signaturePad = new SignaturePad(canvas);

  // Returns signature image as data URL (see https://mdn.io/todataurl for the list of possible parameters)
  signaturePad.toDataURL(); // save image as PNG
  signaturePad.toDataURL("image/jpeg"); // save image as JPEG
  signaturePad.toDataURL("image/svg+xml"); // save image as SVG

  // Draws signature image from data URL.
  // NOTE: This method does not populate internal data structure that represents drawn signature. Thus, after using #fromDataURL, #toData won't work properly.
  signaturePad.fromDataURL("data:image/png;base64,iVBORw0K...");

  // Returns signature image as an array of point groups
  const data = signaturePad.toData();

  // Draws signature image from an array of point groups
  signaturePad.fromData(data);

  // Clears the canvas
  signaturePad.clear();

  // Returns true if canvas is empty, otherwise returns false
  signaturePad.isEmpty();

  // Unbinds all event handlers
  signaturePad.off();

  // Rebinds all event handlers
  signaturePad.on();

  var wrapper = document.getElementById("signature-pad");
//var clearButton = wrapper.querySelector("[data-action=clear]");
var changeColorButton = wrapper.querySelector("[data-action=change-color]");
var savePNGButton = wrapper.querySelector("[data-action=save-png]");
var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas, {
  // It's Necessary to use an opaque color when saving image as JPEG;
  // this option can be omitted if only saving as PNG or SVG
  backgroundColor: 'rgb(255, 255, 255)'
});

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
  // When zoomed out to less than 100%, for some very strange reason,
  // some browsers report devicePixelRatio as less than 1
  // and only part of the canvas is cleared then.
  var ratio =  Math.max(window.devicePixelRatio || 1, 1);

  // This part causes the canvas to be cleared
  canvas.width = canvas.offsetWidth * ratio;
  canvas.height = canvas.offsetHeight * ratio;
  canvas.getContext("2d").scale(ratio, ratio);

  // This library does not listen for canvas changes, so after the canvas is automatically
  // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
  // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
  // that the state of this library is consistent with visual state of the canvas, you
  // have to clear it manually.
  signaturePad.clear();
}

// On mobile devices it might make more sense to listen to orientation change,
// rather than window resize events.
window.onresize = resizeCanvas;
resizeCanvas();

function download(dataURL, filename) {
  if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) {
    window.open(dataURL);
  } else {
    var blob = dataURLToBlob(dataURL);
    var url = window.URL.createObjectURL(blob);

    var a = document.createElement("a");
    a.style = "display: none";
    a.href = url;
    a.download = filename;

    document.body.appendChild(a);
    a.click();

    window.URL.revokeObjectURL(url);
  }
}

// One could simply use Canvas#toBlob method instead, but it's just to show
// that it can be done using result of SignaturePad#toDataURL.
function dataURLToBlob(dataURL) {
  // Code taken from https://github.com/ebidel/filer.js
  var parts = dataURL.split(';base64,');
  var contentType = parts[0].split(":")[1];
  var raw = window.atob(parts[1]);
  var rawLength = raw.length;
  var uInt8Array = new Uint8Array(rawLength);

  for (var i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], { type: contentType });
}


  $(function(){
    $(document).on("click", ".clearSig", function(){
      //console.log(signaturePad);
      signaturePad.clear();
    });
    $(document).on("click", ".saveSig", function(){
      if(signaturePad.isEmpty()){
        alert("Please sign.");
      }else{
        $(".sig").text(signaturePad.toDataURL("image/jpeg"));
        $("#sigForm").submit();
      }
    });
  });

  </script>
</body>
</html>
