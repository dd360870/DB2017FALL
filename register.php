<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>註冊</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <script
			  src="https://code.jquery.com/jquery-3.2.1.js"
			  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
			  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="main.css">
  <script src="main.js"></script>
  <script type='text/javascript'>
  function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmPassword = $("#password2").val();

    if (password != confirmPassword) {
      $("#password2").addClass('is-invalid');
      $("#password2").focus();
      return false;
    }
    else {
      $("#password2").removeClass('is-invalid');
      return true;
    }
}
  </script>
</head>
<body>
  <?php include_once('nav.php'); ?>
  <div class="container">
  <div class='row'>
  <?php
  /*$servername="localhost";
  $username="root";
  $password="dd360870";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=demo;charset=UTF8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected.";
  } catch (PDOException $e) {
    echo "Error.".$e->getMessage()."<br/>";
  }*/
  if($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nickname = $_POST['nickname'];
    $current_time = date("Y-m-d H:i:s");
    $check = $conn->prepare("SELECT `username` FROM `user` WHERE `username` LIKE '$username'");
    $check->execute();
    echo "<div class='col-10 offset-1 text-center'>";
    if($check->rowCount() > 0) {
      echo "<h1>Username already exist. Please login as ".$username.".</h1>".
            "<a class='btn btn-success' href='login.php'>Log In</a>";
    } else {
      $result = $conn->prepare("INSERT INTO `user`".
          "(`username`, `password`, `create_date`, `last_login`, `nickname`)".
          " VALUES ('$username','$password','$current_time','$current_time','$nickname')");
      $result->execute();
      echo "<h1>Create User ".$username." successed.</h1>".
            "<a class='btn btn-success' href='login.php'>Log In</a>";
    }
    echo "</div>";
  }
  else {
    echo '
    <div class="col-10 offset-1 text-center"><h1>REGISTER a new account.</h1></div>
  <div class="col-10 offset-1 col-md-4 offset-md-4">
  <form class="form-bg" onsubmit="return checkPasswordMatch();" action="register.php" autocomplete="off" method="post">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
    </div>
    <div class="form-group">
      <label for="password2">Confirm password</label>
      <input onkeyup="checkPasswordMatch()" type="password" class="form-control" id="password2" placeholder="Confirm password" required>
      <div class="invalid-feedback">Please type same password.</div>
    </div>
    <div class="form-group">
      <label for="nickname">Nickname</label>
      <input type="text" name="nickname" class="form-control" id="nickname" placeholder="Nickname" required>
    </div>
    <input class="btn btn-primary" type="submit" value="Submit" />
  </form></div>';
  }
  ?>
    </div>
  </div>
  <footer id="footer" class="text-center" style="position: relative;bottom: 0;background:rgba(255,255,255,0.2);padding:10px;width:100%;">
    <font style='padding:5px;'>© 2018 Ruzy Lee</font>
    <a href="https://www.freepik.com/free-vector/abstract-background-design_1054590.htm" style='text-decoration:none;padding:5px;'>Background credit by Freepik</a>
  </footer>
</body>
</html>
