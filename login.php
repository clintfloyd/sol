<?php
require('mysql/MysqliDb.php');
require('mysql_connection.php');
$db->autoReconnect = true;

if($_POST){

  $db->where("username",$_POST['username']);
  $db->where("password",sha1($_POST['password']));
  $users = $db->get("users");

  if($db->count >= 1){
    session_start();
    $_SESSION['isLoggedin'] = "true";
    $_SESSION['user'] = $users[0]['name'];
    $_SESSION['email'] = $users[0]['email'];
    $_SESSION['location'] = $users[0]['location'];
    header("Location: index.php");
    die();
  }

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

  <div class="login_form">
    <form method="post" action="login.php">
      <div class="form-group">
        <label for="exampleInputEmail1">Username</label>
        <input type="text" class="form-control" name="username" id="exampleInputEmail1" maxlength="100" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="signup.php" type="submit" class="btn btn-outline-primary">Signup</a>
      </div>
    </form>
  </div>

</body>
</html>
