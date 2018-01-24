<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>留言板</title>
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
  <div class='row'><div class='col-md-6 offset-md-3 col-lg-4 offset-lg-4 col-10 offset-1'>
<?php
if(!empty($_POST)) {
  if(!empty($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $current_time = date("Y-m-d H:i:s");
    $sql =
      "INSERT INTO `organization`".
      "(`name`, `description`, `mgr_uid`, `group_create_date`) VALUES ".
      "('$name','$description',$uid,'$current_time')";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute();
    if($result){
      echo "<h1>Create Group \"".$name."\" successed.</h1>";
      echo "<a class='btn btn-primary btn-lg' href='index.php'>Go to HOME page.</a>";
    } else {
      echo "create group failed. something goes wrong.";
    }
  } else {
    echo "Please login first.";
  }
}
if(!empty($_SESSION['uid']) && empty($_POST)) {
echo '<h1>Create a new group</h1>
<form class="form-bg" action="create_group.php" autocomplete="off" method="post">
  <div class="form-group">
    <label for="name">Group name</label>
    <input type="text" name="name" class="form-control" id="name" placeholder="name" required>
  </div>
  <div class="form-group">
    <label for="desc">Description</label>
    <input type="text" name="description" class="form-control" id="desc" placeholder="Description of your group." required>
  </div>
  <br/>
  <input class="btn btn-primary" type="submit" value="Create" />
</form>';
} else if(empty($_SESSION['uid'])){
  echo "<h1>Error - Please login first.</h1>";
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
