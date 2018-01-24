<?php
include_once('basic.php');
$info = null;
if(!empty($_POST['action']) && $_POST['action'] == 'login') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT * FROM `user` WHERE `username` LIKE '$username'");
  $stmt->execute();
  if($stmt->rowCount()==0){
    $info = "User does not exist."; #output information
  } else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($password == $result['password']) {
      $info = "Login success."; #output information
      $update = "UPDATE `user` SET `last_login`='".date("Y-m-d H:i:s")."' WHERE uid=".$result['uid'];
      $update_stmt = $conn->prepare($update);
      $update_stmt->execute();
      regen_session_id();
      $_SESSION['username'] = $username;
      $_SESSION['uid'] = $result['uid'];
    } else {
      $info = "Incorrect password."; #output information
    }
  }
}
else if(!empty($_POST['action']) && $_POST['action'] == 'logout') {
  session_unset();
  regen_session_id();
}
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>登入</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <script
			  src="https://code.jquery.com/jquery-3.2.1.js"
			  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
			  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="main.css">
  <script src="main.js"></script>
</head>
<body>
<?php include_once('nav.php'); ?>
<div class="container">
  <div class='row'>
<?php
if(!empty($info)) {
  echo "<div class='col-12 text-center'><h1 style='display-4'>".$info."</h1></div>";
}
if (!empty($_SESSION['uid'])) {
  echo "<div class='col-10 offset-1 text-center'><h3>uid = ".$_SESSION['uid']."</h3>";
  echo "<h3>username = ".$_SESSION['username']."</h3>";
  echo '<h6>Redirecting to HOME page after <span id="countdown">10</span> seconds</h6></div>';
}
else {
  echo "<div class='col-10 offset-1 col-md-4 offset-md-4'><a class='btn btn-success' href='register.php' style='margin:5px;padding:5px;font-size:18px;'>Register</a>";
  echo '
  <form class="form-bg" action="login.php" autocomplete="off" method="post">
    <input type="hidden" value="login" name="action"/>
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <input class="btn btn-primary" type="submit" value="Login" />
  </form></div>';
}
?>
</div>
</div>
<script type="text/javascript">
var seconds = 4;
function countdown() {
  seconds = seconds - 1;
  if (seconds == 0) {
    // Chnage your redirection link here
    window.location = "index.php";
  } else {
    // Update remaining seconds
    document.getElementById("countdown").innerHTML = seconds;
    // Count down using javascript
    window.setTimeout("countdown()", 1000);
  }
}
<?php
if(!empty($_SESSION['uid'])) {
  echo '$(document).ready(countdown);';
}
?>
</script>
<footer id="footer" class="text-center" style="position: relative;bottom: 0;background:rgba(255,255,255,0.2);padding:10px;width:100%;">
  <font style='padding:5px;'>© 2018 Ruzy Lee</font>
  <a href="https://www.freepik.com/free-vector/abstract-background-design_1054590.htm" style='text-decoration:none;padding:5px;'>Background credit by Freepik</a>
</footer>
</body>
</html>
