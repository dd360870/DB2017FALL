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
  <div class='container'>
    <div class='row' style='padding:10px;'>
      <div class='col-12'>
        <a class='btn btn-primary' style='margin:5px;' href='create_group.php'>Create a new Group</a>
        <form style='float:right;' class='text-center'>
          <div class='form-row'>
            <div class='col-8 mb-3'><input name='str' type='text' class='form-control' placeholder='Keyword'></div>
            <div class='col-4 mb-3'><input type='submit' value='Search' class='btn'></div>
          </div>
        </form>
      </div>
    </div>
    <div class='row' style='padding:10px;'>
      <div class='col-12 text-center'>
<?php
/*echo "Your SESSION ID : ".session_id()."<br/>";*/
if(!empty($_SESSION['uid'])) {
  $current_uid = $_SESSION['uid'];
  $sql = "SELECT * FROM `organization` JOIN `user` ON mgr_uid = uid";
  if(!empty($_GET['str'])) {
    $str = $_GET['str'];
    $sql .= " WHERE `name` LIKE '%$str%' OR `description` LIKE '%$str%'";
  }
  $sql .= " ORDER BY group_create_date DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  if($stmt->rowCount()==0){
    echo "<h1 class='display-4' style='text-align:center;'>No result.</h1>
          <button class='btn btn-success' style='margin: 30px;' onclick='window.location.replace(\"index.php\")'>Back</button>";
  } else {
    echo "<table style='margin: 0 auto;' class='table table-striped table-dark text-center'>".
          "<tr><th scope='col'>Name</th>".
          "<th scope='col' class='td-hide'>Description</th>".
          "<th scope='col'>Manager</th>".
          "<th scope='col' class='td-hide'>Create Date</th>".
          "<th scope='col' class='td-hide'>Members</th>".
          "<th scope='col'>Action</th></tr>";
    $datalist = $stmt->fetchAll();
    foreach($datalist as $row) {
      echo "<tr><td>";
      if(isGroupMember($conn, $row['gid'], $current_uid)) {
        echo "<a href='group.php?gid=".$row['gid']."&action=post'>".$row['name']."</a>";
      } else {
        echo $row['name'];
      }
      echo "</td><td class='td-hide'>".$row['description']."</td>".
            "<td>".$row['nickname']."</td><td class='td-hide'>".$row['group_create_date']."</td>".
            "<td class='td-hide'>".groupMemberCount($conn, $row['gid'])."</td><td>";
      $gid = $row['gid'];
      if(!empty($current_uid) && $current_uid==$row['mgr_uid']) {
        //echo "<button class='btn btn-outline-warning' onclick='delete_group($gid)'>Delete</button>";
      } else if(!isGroupMember($conn, $row['gid'], $current_uid) && !isApplied($conn, $gid, $current_uid)){
        echo "<button class='btn btn-success' onclick='apply($gid)'>Apply</button>";
      } else if(isApplied($conn, $gid, $current_uid)) {
        echo "Applied.";
      }
      echo "</td></tr>";
    }
    echo "</table>";
  }
} else {
  echo "<h1 style='margin: 30px auto;'>Please login first.</h1>";
}
?>
</div></div>
</div><!-- .container -->

<footer id="footer" class="text-center" style="position: relative;bottom: 0;background:rgba(255,255,255,0.2);padding:10px;width:100%;">
  <font style='padding:5px;'>© 2018 Ruzy Lee</font>
  <a href="https://www.freepik.com/free-vector/abstract-background-design_1054590.htm" style='text-decoration:none;padding:5px;'>Background credit by Freepik</a>
</footer>
</body>
</html>
