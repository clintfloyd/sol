<?php
session_start();
require('mysql/MysqliDb.php');
require('mysql_connection.php');
$db->autoReconnect = true;

$locations = $db->get("locations");

if($_POST){
  $data = $_POST;

  $data['password'] = sha1($_POST['password']);
  $id = $db->insert ("users", $data);
  header("Location: login.php?success");
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
<body class="login">

  <div class="login_form signup">
    <form method="post" action="signup.php">
      <div class="form-group">
        <label for="exampleInputEmail1">Name</label>
        <input type="text" name="name" class="form-control" id="exampleInputEmail1" maxlength="100" required="required" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" maxlength="100" required="required" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">Username</label>
        <input type="text" name="username" class="form-control" id="exampleInputEmail1" maxlength="100" required="required" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" required="required" id="exampleInputPassword1">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Location</label>
        <select class="form-control" name="location">
          <option value="">Select Location</option>
          <?php
          foreach($locations as $location){
            ?>
            <option value="<?php echo $location['id']; ?>"><?php echo $location['location_name']; ?></option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="login.php" type="submit" class="btn btn-outline-primary">Cancel</a>
      </div>
    </form>
  </div>

</body>
</html>
